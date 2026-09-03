<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{TrainingEnrollment, TrainingBatch, User, TrainingNomination};
use App\Models\Notification;
use App\Mail\EnrollmentNotification;
use App\Support\AsyncMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = TrainingEnrollment::with([
            'trainee.traineeProfile',
            'trainingBatch.trainingProgram',
            'enrolledBy'
        ])->latest()->paginate(20);
        
        return view('admin.enrollments.index', compact('enrollments'));
    }
    
    public function create()
    {
        $trainees = User::where('user_type', 'trainee')
            ->where('is_active', true)
            ->whereDoesntHave('enrollments', function ($query) {
                $query->whereIn('status', ['enrolled', 'in_progress']);
            })
            ->with(['traineeProfile.user'])
            ->orderBy('name')
            ->get();

        $enrolledTrainees = User::where('user_type', 'trainee')
            ->where('is_active', true)
            ->whereHas('enrollments', function ($query) {
                $query->whereIn('status', ['enrolled', 'in_progress']);
            })
            ->with([
                'traineeProfile',
                'enrollments' => function ($query) {
                    $query->whereIn('status', ['enrolled', 'in_progress'])
                        ->with('trainingBatch.trainingProgram');
                },
            ])
            ->orderBy('name')
            ->get();

        $batches = TrainingBatch::with('trainingProgram')
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->orderBy('start_date')
            ->get();

        return view('admin.enrollments.create', compact('trainees', 'enrolledTrainees', 'batches'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trainee_ids' => 'required|array|min:1',
            'trainee_ids.*' => 'exists:users,id',
            'training_batch_id' => 'required|exists:training_batches,id',
            'nomination_id' => 'nullable|exists:training_nominations,id',
            'enrollment_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $batch = TrainingBatch::with('trainingProgram')->findOrFail($validated['training_batch_id']);
        $traineeIds = array_values(array_unique($validated['trainee_ids']));
        $availableSeats = max(0, $batch->total_seats - $batch->seats_filled);

        if (count($traineeIds) > $availableSeats) {
            return back()
                ->withInput()
                ->with('error', 'Not enough seats in this batch. Available: ' . $availableSeats . ', selected: ' . count($traineeIds) . '.');
        }

        $enrolledCount = 0;
        $skipped = [];

        DB::transaction(function () use ($validated, $batch, $traineeIds, &$enrolledCount, &$skipped) {
            foreach ($traineeIds as $traineeId) {
                $batch->refresh();

                $existingEnrollment = TrainingEnrollment::where('trainee_id', $traineeId)
                    ->where('training_batch_id', $batch->id)
                    ->first();

                if ($existingEnrollment) {
                    $skipped[] = User::find($traineeId)?->name ?? "Trainee #{$traineeId}";
                    continue;
                }

                if ($batch->seats_filled >= $batch->total_seats) {
                    $skipped[] = User::find($traineeId)?->name ?? "Trainee #{$traineeId}";
                    continue;
                }

                $enrollment = TrainingEnrollment::create([
                    'trainee_id' => $traineeId,
                    'training_batch_id' => $batch->id,
                    'nomination_id' => $validated['nomination_id'] ?? null,
                    'enrollment_date' => $validated['enrollment_date'],
                    'remarks' => ($validated['remarks'] ?? '') !== '' ? $validated['remarks'] : null,
                    'enrolled_by' => Auth::id(),
                    'status' => 'enrolled',
                ]);

                $batch->increment('seats_filled');

                $trainee = User::find($traineeId);
                if ($trainee) {
                    Notification::create([
                        'user_id' => $trainee->id,
                        'type' => 'enrollment',
                        'title' => 'Training Enrollment',
                        'message' => 'You have been enrolled in ' . $batch->trainingProgram->title,
                        'data' => [
                            'enrollment_id' => $enrollment->id,
                            'batch_id' => $batch->id,
                            'url' => route('trainee.dashboard'),
                            'icon' => 'journal-check',
                        ],
                    ]);

                    AsyncMail::sendAfterResponse(function () use ($trainee, $enrollment) {
                        Mail::to($trainee->email)->send(new EnrollmentNotification($enrollment));
                    });
                }

                activity()
                    ->performedOn($enrollment)
                    ->causedBy(Auth::user())
                    ->log('Trainee enrolled in training batch');

                $enrolledCount++;
            }
        });

        if (! empty($validated['nomination_id']) && $enrolledCount > 0) {
            TrainingNomination::find($validated['nomination_id'])?->update(['status' => 'approved']);
        }

        if ($enrolledCount === 0) {
            $message = 'No trainees were enrolled.';
            if (! empty($skipped)) {
                $message .= ' Skipped: ' . implode(', ', $skipped) . '.';
            }

            return back()->withInput()->with('error', $message);
        }

        $successMessage = $enrolledCount === 1
            ? '1 trainee enrolled successfully!'
            : "{$enrolledCount} trainees enrolled successfully!";

        if (! empty($skipped)) {
            $successMessage .= ' Skipped: ' . implode(', ', $skipped) . '.';
        }

        return redirect()->route('admin.enrollments.index')->with('success', $successMessage);
    }
    
    public function show($id)
    {
        $enrollment = TrainingEnrollment::with([
            'trainee.traineeProfile.organization',
            'trainingBatch.trainingProgram',
            'trainingBatch.trainers',
            'enrolledBy',
            'attendanceRecords.trainingSession',
            'assessmentResults.assessment',
        ])->findOrFail($id);
        
        return view('admin.enrollments.show', compact('enrollment'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:enrolled,in_progress,completed,dropped,failed',
            'completion_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);
        
        $enrollment = TrainingEnrollment::findOrFail($id);
        $enrollment->update($validated);
        
        // Log activity
        activity()
            ->performedOn($enrollment)
            ->causedBy(Auth::user())
            ->log('Enrollment status updated to ' . $validated['status']);
        
        return back()->with('success', 'Enrollment status updated successfully!');
    }
}
