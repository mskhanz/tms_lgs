<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\TrainingEnrollment;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()
            ->with([
                'trainingBatch.trainingProgram',
                'attendanceRecords.trainingSession',
            ])
            ->withCount('attendanceRecords')
            ->latest('enrollment_date')
            ->get()
            ->filter(function (TrainingEnrollment $enrollment) {
                $batch = $enrollment->trainingBatch;

                return $batch
                    && ($batch->isAttendanceActive() || $batch->isAttendanceEnabled() || $enrollment->attendance_records_count > 0);
            })
            ->values();

        $totalSessions = 0;
        $presentCount = 0;

        foreach ($enrollments as $enrollment) {
            foreach ($enrollment->attendanceRecords as $record) {
                $totalSessions++;
                if (in_array($record->status, ['present', 'late'], true)) {
                    $presentCount++;
                }
            }
        }

        $overallPercentage = $totalSessions > 0
            ? round(($presentCount / $totalSessions) * 100, 1)
            : 0;

        return view('trainee.attendance.index', compact('enrollments', 'overallPercentage', 'totalSessions', 'presentCount'));
    }

    public function show(TrainingEnrollment $enrollment)
    {
        abort_unless((int) $enrollment->trainee_id === (int) Auth::id(), 403);

        $enrollment->load([
            'trainingBatch.trainingProgram',
            'trainingBatch.sessions' => fn ($query) => $query->orderBy('session_date')->orderBy('start_time'),
            'attendanceRecords.trainingSession',
        ]);

        $batch = $enrollment->trainingBatch;

        if (! $batch || (! $batch->isAttendanceEnabled() && $enrollment->attendanceRecords->isEmpty())) {
            return redirect()
                ->route('trainee.attendance.index')
                ->with('error', 'Attendance is not available for this training.');
        }

        $records = $enrollment->attendanceRecords->keyBy('training_session_id');

        $sessionRows = ($batch->sessions ?? collect())->map(function ($session) use ($records) {
            $record = $records->get($session->id);

            return (object) [
                'session' => $session,
                'record' => $record,
                'status' => $record?->status ?? 'not_marked',
            ];
        });

        $statusCounts = [
            'present' => $sessionRows->where('status', 'present')->count(),
            'absent' => $sessionRows->where('status', 'absent')->count(),
            'late' => $sessionRows->where('status', 'late')->count(),
            'excused' => $sessionRows->where('status', 'excused')->count(),
            'not_marked' => $sessionRows->where('status', 'not_marked')->count(),
        ];

        return view('trainee.attendance.show', compact('enrollment', 'batch', 'sessionRows', 'statusCounts'));
    }
}
