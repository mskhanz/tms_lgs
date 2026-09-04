<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentAttachment;
use App\Models\AssignmentSubmission;
use App\Models\AssignmentSubmissionFile;
use App\Support\AssignmentFileStorage;
use App\Support\HtmlContent;
use App\Support\TraineeAssignmentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function index()
    {
        $data = TraineeAssignmentData::forIndex(Auth::id());

        return view('trainee.assignments.index', $data);
    }

    public function show(Assignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $assignment->load(['attachments', 'trainingProgram', 'trainingBatch.trainingProgram']);
        $submission = $assignment->submissionFor(Auth::id());
        if ($submission) {
            $submission->load('files');
        }

        return view('trainee.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        if (! $assignment->isAvailable()) {
            return back()->with('error', 'This assignment is not available.');
        }

        $existing = $assignment->submissionFor(Auth::id());
        if ($existing?->isSubmitted() && ! $assignment->canTraineeEditSubmission()) {
            return back()->with('error', 'The due date has passed. You can no longer update this submission.');
        }

        $mimes = AssignmentFileStorage::ALLOWED_MIMES;
        $maxKb = AssignmentFileStorage::MAX_KB;

        $validated = $request->validate([
            'written_response' => 'nullable|string|max:100000',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:'.$mimes.'|max:'.$maxKb,
            'remove_files' => 'nullable|array',
            'remove_files.*' => 'integer',
            'action' => 'required|in:draft,submit',
        ]);

        $validated['written_response'] = HtmlContent::sanitize($validated['written_response'] ?? null);
        $hasText = ! HtmlContent::isEmpty($validated['written_response']);
        $hasNewFiles = ! empty($request->file('files'));
        $keepingFiles = $existing
            ? $existing->files()->whereNotIn('id', (array) ($validated['remove_files'] ?? []))->exists()
            : false;

        if ($validated['action'] === 'submit' && ! $hasText && ! $hasNewFiles && ! $keepingFiles) {
            return back()->withInput()->with('error', 'Please write a response or upload at least one file before submitting.');
        }

        DB::transaction(function () use ($assignment, $validated, $request, $existing) {
            $submission = $existing ?: new AssignmentSubmission([
                'assignment_id' => $assignment->id,
                'user_id' => Auth::id(),
            ]);

            $submission->written_response = $validated['written_response'];

            if ($validated['action'] === 'submit') {
                $submission->status = 'submitted';
                $submission->submitted_at = now();
            } else {
                $submission->status = 'draft';
                if (! $submission->exists) {
                    $submission->submitted_at = null;
                }
            }

            $submission->save();

            foreach ((array) ($validated['remove_files'] ?? []) as $fileId) {
                $file = $submission->files()->where('id', $fileId)->first();
                if ($file) {
                    AssignmentFileStorage::delete($file->stored_name);
                    $file->delete();
                }
            }

            foreach ((array) $request->file('files', []) as $upload) {
                if (! $upload) {
                    continue;
                }
                $meta = AssignmentFileStorage::store($upload, 'sub'.$submission->id);
                $submission->files()->create($meta);
            }
        });

        $message = $validated['action'] === 'submit'
            ? 'Assignment submitted successfully.'
            : 'Draft saved successfully.';

        if ($validated['action'] === 'submit') {
            return redirect()->route('trainee.assignments.index')
                ->with('success', $message);
        }

        return redirect()->route('trainee.assignments.show', $assignment)
            ->with('success', $message);
    }

    public function downloadAttachment(Assignment $assignment, AssignmentAttachment $attachment)
    {
        $this->authorizeAssignment($assignment);
        abort_unless($attachment->assignment_id === $assignment->id, 404);
        abort_unless($attachment->existsOnDisk(), 404);

        return response()->download($attachment->absolutePath(), $attachment->original_name);
    }

    public function viewAttachment(Assignment $assignment, AssignmentAttachment $attachment)
    {
        $this->authorizeAssignment($assignment);
        abort_unless($attachment->assignment_id === $assignment->id, 404);
        abort_unless($attachment->existsOnDisk(), 404);

        $mime = $attachment->mime_type ?: 'application/octet-stream';

        return response()->file($attachment->absolutePath(), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.$attachment->original_name.'"',
        ]);
    }

    public function downloadSubmissionFile(Assignment $assignment, AssignmentSubmissionFile $file)
    {
        $this->authorizeAssignment($assignment);
        $submission = $file->submission;
        abort_unless($submission && $submission->assignment_id === $assignment->id, 404);
        abort_unless($submission->user_id === Auth::id(), 403);
        abort_unless($file->existsOnDisk(), 404);

        return response()->download($file->absolutePath(), $file->original_name);
    }

    public function viewSubmissionFile(Assignment $assignment, AssignmentSubmissionFile $file)
    {
        $this->authorizeAssignment($assignment);
        $submission = $file->submission;
        abort_unless($submission && $submission->assignment_id === $assignment->id, 404);
        abort_unless($submission->user_id === Auth::id(), 403);
        abort_unless($file->existsOnDisk(), 404);

        $mime = $file->mime_type ?: 'application/octet-stream';

        return response()->file($file->absolutePath(), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.$file->original_name.'"',
        ]);
    }

    private function authorizeAssignment(Assignment $assignment): void
    {
        abort_unless($assignment->is_active, 404);
        abort_unless($assignment->isAssignedToTrainee(Auth::id()), 403);
    }
}
