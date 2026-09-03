<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingBatch;
use App\Models\TrainingProgram;
use App\Models\TrainingSession;
use App\Models\TrainingSessionType;
use App\Models\AttendanceChangeLog;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $reportDate = $request->input('report_date', now()->toDateString());

        $programs = TrainingProgram::with(['batches' => function ($query) {
            $query->with('trainingProgram')
                ->withCount('enrollments', 'sessions')
                ->orderBy('start_date');
        }])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('title')
            ->get();

        $sessionsForDate = $this->sessionsForDate($reportDate);

        return view('admin.attendance.index', compact('programs', 'sessionsForDate', 'reportDate'));
    }

    public function sessionReport(Request $request)
    {
        $reportDate = $request->input('date', now()->toDateString());
        $sessionsForDate = $this->sessionsForDate($reportDate);

        return view('admin.attendance.session-report', compact('sessionsForDate', 'reportDate'));
    }

    public function toggleProgram(TrainingProgram $program)
    {
        $program->update([
            'attendance_enabled' => ! $program->attendance_enabled,
        ]);

        $state = $program->attendance_enabled ? 'enabled' : 'disabled';

        activity()
            ->performedOn($program)
            ->causedBy(Auth::user())
            ->log("Attendance {$state} for training program");

        return back()->with('success', "Attendance {$state} for program {$program->code}.");
    }

    public function toggleBatch(TrainingBatch $batch)
    {
        if (! $batch->trainingProgram?->isAttendanceEnabled()) {
            return back()->with('error', 'Enable attendance on the training program first.');
        }

        $batch->update([
            'attendance_enabled' => ! $batch->attendance_enabled,
        ]);

        $state = $batch->attendance_enabled ? 'enabled' : 'disabled';

        activity()
            ->performedOn($batch)
            ->causedBy(Auth::user())
            ->log("Attendance {$state} for training batch");

        return back()->with('success', "Attendance {$state} for batch {$batch->batch_code}.");
    }

    public function activateProgramBatches(TrainingProgram $program)
    {
        if (! $program->isAttendanceEnabled()) {
            return back()->with('error', 'Enable attendance on the program before activating batches.');
        }

        $updated = $program->batches()->update(['attendance_enabled' => true]);

        return back()->with('success', "Attendance activated for {$updated} batch(es) under {$program->code}.");
    }

    public function showBatch(TrainingBatch $batch)
    {
        $batch->load([
            'trainingProgram',
            'enrollments.trainee.traineeProfile',
            'sessions' => fn ($query) => $query->with('sessionType')->withCount('attendanceRecords')->orderBy('session_date'),
        ]);

        if (! $batch->isAttendanceActive()) {
            return redirect()
                ->route('admin.attendance.index')
                ->with('error', 'Attendance is not active for this batch. Enable it from the attendance page first.');
        }

        $sessionTypes = TrainingSessionType::where('is_active', true)->orderBy('sort_order')->get();
        $usedSessionKeys = $batch->sessions
            ->map(fn (TrainingSession $session) => $session->session_date?->format('Y-m-d').'|'.($session->training_session_type_id ?? ''))
            ->filter()
            ->values();

        return view('admin.attendance.batch', compact('batch', 'sessionTypes', 'usedSessionKeys'));
    }

    public function storeSession(Request $request, TrainingBatch $batch)
    {
        if (! $batch->isAttendanceActive()) {
            return back()->with('error', 'Attendance is not active for this batch.');
        }

        $validated = $request->validate([
            'training_session_type_id' => 'required|exists:training_session_types,id',
            'session_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (! AttendanceService::canScheduleSessionOnDate($validated['session_date'])) {
            return back()
                ->withInput()
                ->withErrors(['session_date' => 'Sessions cannot be scheduled on a previous date.']);
        }

        $sessionType = TrainingSessionType::findOrFail($validated['training_session_type_id']);

        $duplicate = $batch->sessions()
            ->whereDate('session_date', $validated['session_date'])
            ->where('training_session_type_id', $validated['training_session_type_id'])
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'training_session_type_id' => "{$sessionType->name} is already scheduled for this date.",
                ]);
        }

        $session = $batch->sessions()->create([
            ...$validated,
            'title' => $sessionType->name,
            'status' => 'scheduled',
        ]);

        activity()
            ->performedOn($session)
            ->causedBy(Auth::user())
            ->log('Training session created for attendance');

        return back()->with('success', 'Session created successfully.');
    }

    public function markSession(TrainingBatch $batch, TrainingSession $session)
    {
        if ((int) $session->training_batch_id !== (int) $batch->id) {
            abort(404);
        }

        if (! $batch->isAttendanceActive()) {
            return redirect()
                ->route('admin.attendance.index')
                ->with('error', 'Attendance is not active for this batch.');
        }

        $batch->load([
            'trainingProgram',
            'enrollments.trainee.traineeProfile',
        ]);

        $existingMarks = $session->attendanceRecords()
            ->get()
            ->keyBy('enrollment_id');

        $canEdit = AttendanceService::canMarkAttendanceForSession($session);

        $changeLogs = AttendanceChangeLog::with(['trainee', 'changedBy'])
            ->where('training_session_id', $session->id)
            ->latest()
            ->limit(50)
            ->get();

        return view('admin.attendance.mark', compact('batch', 'session', 'existingMarks', 'canEdit', 'changeLogs'));
    }

    public function saveMarks(Request $request, TrainingBatch $batch, TrainingSession $session)
    {
        if ((int) $session->training_batch_id !== (int) $batch->id) {
            abort(404);
        }

        if (! $batch->isAttendanceActive()) {
            return back()->with('error', 'Attendance is not active for this batch.');
        }

        if (! AttendanceService::canMarkAttendanceForSession($session)) {
            return back()->with('error', 'Attendance for previous dates cannot be changed. Only today\'s session attendance can be marked or updated.');
        }

        $validated = $request->validate([
            'marks' => 'required|array',
            'marks.*.check_in_time' => 'nullable|date_format:H:i',
            'marks.*.status' => 'required|in:present,absent,late,excused',
            'marks.*.remarks' => 'nullable|string|max:255',
        ]);

        try {
            AttendanceService::saveSessionMarks($session, $validated['marks'], Auth::id());
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($session->status === 'scheduled') {
            $session->update(['status' => 'completed']);
        }

        activity()
            ->performedOn($session)
            ->causedBy(Auth::user())
            ->log('Attendance marked for training session');

        return redirect()
            ->route('admin.batches.attendance.show', $batch)
            ->with('success', 'Attendance saved successfully.');
    }

    private function sessionsForDate(string $reportDate)
    {
        return TrainingSession::with([
            'trainingBatch.trainingProgram',
            'trainingBatch.enrollments.trainee.traineeProfile',
            'attendanceRecords',
        ])
            ->whereDate('session_date', $reportDate)
            ->whereHas('trainingBatch', function ($query) {
                $query->where('attendance_enabled', true);
            })
            ->orderBy('start_time')
            ->orderBy('title')
            ->get()
            ->map(function (TrainingSession $session) {
                $records = $session->attendanceRecords->keyBy('enrollment_id');
                $enrollments = $session->trainingBatch?->enrollments ?? collect();

                $rows = $enrollments->map(function ($enrollment) use ($records) {
                    $record = $records->get($enrollment->id);

                    return [
                        'enrollment' => $enrollment,
                        'record' => $record,
                        'status' => $record?->status ?? 'not_marked',
                    ];
                });

                $statusCounts = [
                    'present' => $rows->where('status', 'present')->count(),
                    'absent' => $rows->where('status', 'absent')->count(),
                    'late' => $rows->where('status', 'late')->count(),
                    'excused' => $rows->where('status', 'excused')->count(),
                    'not_marked' => $rows->where('status', 'not_marked')->count(),
                ];

                return (object) [
                    'session' => $session,
                    'batch' => $session->trainingBatch,
                    'program' => $session->trainingBatch?->trainingProgram,
                    'rows' => $rows,
                    'statusCounts' => $statusCounts,
                    'totalEnrolled' => $enrollments->count(),
                ];
            });
    }
}
