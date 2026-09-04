<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AssignmentPublished;
use App\Models\Assignment;
use App\Models\AssignmentAttachment;
use App\Models\AssignmentSubmission;
use App\Models\AssignmentSubmissionFile;
use App\Models\Notification;
use App\Models\TrainingBatch;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Support\AssignmentFileStorage;
use App\Support\AsyncMail;
use App\Support\HtmlContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::withCount(['attachments', 'submissions'])
            ->with(['creator', 'trainingProgram', 'trainingBatch.trainingProgram']);

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('instructions', 'like', "%{$search}%");
            });
        }

        $assignments = $query->latest()->paginate(15);

        return view('admin.assignments.index', compact('assignments'));
    }

    public function create()
    {
        return view('admin.assignments.create', $this->assignmentFormData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateAssignment($request);
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active');
        $validated = $this->normalizeAssignment($validated);

        $assignment = DB::transaction(function () use ($validated, $request) {
            $assignment = Assignment::create($validated);
            $this->storeAttachments($assignment, $request->input('materials', []), $request->file('materials', []));

            return $assignment;
        });

        if ($assignment->is_active) {
            $this->notifyAssignedTrainees($assignment);
            $assignment->update(['published_at' => now()]);
        }

        activity()
            ->performedOn($assignment)
            ->causedBy(Auth::user())
            ->log('Assignment created');

        return redirect()->route('admin.assignments.show', $assignment)
            ->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load([
            'attachments',
            'creator',
            'trainingProgram',
            'trainingBatch.trainingProgram',
            'submissions' => fn ($q) => $q->orderByDesc('submitted_at')->orderBy('id'),
            'submissions.user.traineeProfile',
            'submissions.files',
        ]);

        $assignedCount = count($assignment->assignedTraineeIds());
        $submittedCount = $assignment->submissions->where('status', 'submitted')->count();

        return view('admin.assignments.show', compact('assignment', 'assignedCount', 'submittedCount'));
    }

    public function edit(Assignment $assignment)
    {
        $assignment->load('attachments');

        return view('admin.assignments.edit', array_merge(
            compact('assignment'),
            $this->assignmentFormData()
        ));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $wasActive = $assignment->is_active;
        $validated = $this->validateAssignment($request);
        $validated['is_active'] = $request->boolean('is_active');
        $validated = $this->normalizeAssignment($validated);

        DB::transaction(function () use ($assignment, $validated, $request) {
            $assignment->update($validated);
            $this->storeAttachments($assignment, $request->input('materials', []), $request->file('materials', []));

            foreach ((array) $request->input('remove_attachments', []) as $attachmentId) {
                $attachment = $assignment->attachments()->where('id', $attachmentId)->first();
                if ($attachment) {
                    AssignmentFileStorage::delete($attachment->stored_name);
                    $attachment->delete();
                }
            }

            foreach ((array) $request->input('existing_titles', []) as $attachmentId => $title) {
                $attachment = $assignment->attachments()->where('id', $attachmentId)->first();
                if ($attachment) {
                    $attachment->update([
                        'title' => trim((string) $title) !== '' ? trim((string) $title) : $attachment->title,
                    ]);
                }
            }
        });

        $assignment->refresh();

        if ($assignment->is_active && (! $wasActive || ! $assignment->published_at)) {
            $this->notifyAssignedTrainees($assignment);
            $assignment->update(['published_at' => now()]);
        }

        return redirect()->route('admin.assignments.show', $assignment)
            ->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        foreach ($assignment->attachments as $attachment) {
            AssignmentFileStorage::delete($attachment->stored_name);
        }

        foreach ($assignment->submissions as $submission) {
            foreach ($submission->files as $file) {
                AssignmentFileStorage::delete($file->stored_name);
            }
        }

        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }

    public function toggleStatus(Assignment $assignment)
    {
        $assignment->update(['is_active' => ! $assignment->is_active]);

        if ($assignment->is_active && ! $assignment->published_at) {
            $this->notifyAssignedTrainees($assignment);
            $assignment->update(['published_at' => now()]);
        }

        return back()->with('success', 'Assignment status updated.');
    }

    public function downloadAttachment(Assignment $assignment, AssignmentAttachment $attachment)
    {
        abort_unless($attachment->assignment_id === $assignment->id, 404);
        abort_unless($attachment->existsOnDisk(), 404);

        return response()->download($attachment->absolutePath(), $attachment->original_name);
    }

    public function viewAttachment(Assignment $assignment, AssignmentAttachment $attachment)
    {
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
        $submission = $file->submission;
        abort_unless($submission && $submission->assignment_id === $assignment->id, 404);
        abort_unless($file->existsOnDisk(), 404);

        return response()->download($file->absolutePath(), $file->original_name);
    }

    public function viewSubmissionFile(Assignment $assignment, AssignmentSubmissionFile $file)
    {
        $submission = $file->submission;
        abort_unless($submission && $submission->assignment_id === $assignment->id, 404);
        abort_unless($file->existsOnDisk(), 404);

        $mime = $file->mime_type ?: 'application/octet-stream';

        return response()->file($file->absolutePath(), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.$file->original_name.'"',
        ]);
    }

    public function showSubmission(Assignment $assignment, AssignmentSubmission $submission)
    {
        abort_unless($submission->assignment_id === $assignment->id, 404);

        $assignment->load(['trainingProgram', 'trainingBatch.trainingProgram', 'attachments']);
        $submission->load(['user.traineeProfile.organization', 'files']);

        return view('admin.assignments.submission', compact('assignment', 'submission'));
    }

    public function updateSubmissionFeedback(Request $request, Assignment $assignment, AssignmentSubmission $submission)
    {
        abort_unless($submission->assignment_id === $assignment->id, 404);

        $validated = $request->validate([
            'marks' => 'nullable|numeric|min:0|max:'.((float) ($assignment->total_marks ?: 9999)),
            'admin_feedback' => 'nullable|string|max:5000',
        ]);

        $submission->update($validated);

        return back()->with('success', 'Feedback saved.');
    }

    private function notifyAssignedTrainees(Assignment $assignment): void
    {
        $assignment->loadMissing(['trainingProgram', 'trainingBatch.trainingProgram']);
        $traineeIds = $assignment->assignedTraineeIds();

        if ($traineeIds === []) {
            return;
        }

        $trainees = User::query()->whereIn('id', $traineeIds)->get();

        foreach ($trainees as $trainee) {
            Notification::create([
                'user_id' => $trainee->id,
                'type' => 'assignment',
                'title' => 'New Assignment',
                'message' => 'A new assignment has been published: '.$assignment->title,
                'data' => [
                    'assignment_id' => $assignment->id,
                    'url' => route('trainee.assignments.show', $assignment),
                    'icon' => 'file-earmark-text',
                ],
            ]);

            if ($trainee->email) {
                AsyncMail::sendAfterResponse(function () use ($trainee, $assignment) {
                    Mail::to($trainee->email)->send(new AssignmentPublished($assignment, $trainee));
                });
            }
        }
    }

    private function storeAttachments(Assignment $assignment, array $materialsMeta, array $materialsFiles): void
    {
        foreach ($materialsMeta as $index => $row) {
            $file = $materialsFiles[$index]['file'] ?? null;
            if (! $file) {
                continue;
            }

            $title = trim((string) ($row['title'] ?? ''));
            $meta = AssignmentFileStorage::store($file, 'assign'.$assignment->id);
            $meta['title'] = $title !== '' ? $title : pathinfo($meta['original_name'], PATHINFO_FILENAME);

            $assignment->attachments()->create($meta);
        }
    }

    private function validateAssignment(Request $request): array
    {
        $mimes = AssignmentFileStorage::ALLOWED_MIMES;
        $maxKb = AssignmentFileStorage::MAX_KB;

        return $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'total_marks' => 'required|numeric|min:1|max:9999',
            'assign_to' => 'required|in:program,batch',
            'training_program_id' => 'required_if:assign_to,program|nullable|exists:training_programs,id',
            'training_batch_id' => 'required_if:assign_to,batch|nullable|exists:training_batches,id',
            'due_at' => 'nullable|date',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
            'materials' => 'nullable|array',
            'materials.*.title' => 'nullable|string|max:255',
            'materials.*.file' => 'nullable|file|mimes:'.$mimes.'|max:'.$maxKb,
            'existing_titles' => 'nullable|array',
            'existing_titles.*' => 'nullable|string|max:255',
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => 'integer',
        ]);
    }

    private function assignmentFormData(): array
    {
        return [
            'programs' => TrainingProgram::orderBy('title')->get(['id', 'code', 'title']),
            'batches' => TrainingBatch::with('trainingProgram:id,title,code')
                ->orderByDesc('start_date')
                ->get(['id', 'training_program_id', 'batch_code', 'start_date', 'end_date', 'status']),
        ];
    }

    private function normalizeAssignment(array $validated): array
    {
        $validated['instructions'] = HtmlContent::sanitize($validated['instructions'] ?? null);

        if (($validated['assign_to'] ?? null) === 'program') {
            $validated['training_batch_id'] = null;
        }

        if (($validated['assign_to'] ?? null) === 'batch') {
            $batch = TrainingBatch::find($validated['training_batch_id'] ?? null);
            $validated['training_program_id'] = $batch?->training_program_id;
        }

        return $validated;
    }
}
