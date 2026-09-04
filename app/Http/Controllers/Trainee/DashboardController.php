<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Support\TraineeAssignmentData;
use App\Support\TraineeQuizData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $traineeProfile = $user->traineeProfile()->with(['organization', 'district'])->first();
        $today = Date::today();
        
        // Get enrollment statistics
        $enrollments = $user->enrollments()->with('trainingBatch.trainingProgram')->get();
        $totalEnrollments = $enrollments->count();
        $ongoingEnrollments = $enrollments->where('status', 'in_progress')->count();
        $completedEnrollments = $enrollments->where('status', 'completed')->count();
        $enrolledCount = $enrollments->where('status', 'enrolled')->count();
        
        // Get certificates
        $certificates = $user->certificates()->count();

        $totalSessions = 0;
        $presentCount = 0;
        $attendanceBatches = 0;
        $todayAttendanceRows = collect();

        $attendanceEnrollments = $user->enrollments()
            ->with([
                'trainingBatch.trainingProgram',
                'trainingBatch.sessions' => fn ($query) => $query
                    ->whereDate('session_date', $today)
                    ->orderBy('start_time'),
                'trainingBatch.sessions.sessionType',
                'attendanceRecords.trainingSession',
            ])
            ->withCount('attendanceRecords')
            ->get();

        foreach ($attendanceEnrollments as $enrollment) {
            $batch = $enrollment->trainingBatch;
            if (! $batch || (! $batch->isAttendanceEnabled() && $enrollment->attendance_records_count === 0)) {
                continue;
            }

            $attendanceBatches++;
            $recordsBySession = $enrollment->attendanceRecords->keyBy('training_session_id');

            foreach ($enrollment->attendanceRecords as $record) {
                $totalSessions++;
                if (in_array($record->status, ['present', 'late'], true)) {
                    $presentCount++;
                }
            }

            foreach ($batch->sessions as $session) {
                $record = $recordsBySession->get($session->id);

                $todayAttendanceRows->push((object) [
                    'enrollment' => $enrollment,
                    'session' => $session,
                    'batch' => $batch,
                    'program' => $batch->trainingProgram,
                    'status' => $record?->status ?? 'not_marked',
                    'check_in_time' => $record?->check_in_time,
                ]);
            }
        }

        $overallAttendance = $totalSessions > 0
            ? round(($presentCount / $totalSessions) * 100, 1)
            : null;
        
        // Get recent enrollments
        $recentEnrollments = $user->enrollments()
            ->with(['trainingBatch.trainingProgram', 'trainingBatch.trainingProgram.conductingOrganization'])
            ->latest('enrollment_date')
            ->limit(5)
            ->get();
        
        // Get notifications
        $notifications = $user->notifications()->unread()->latest()->limit(5)->get();
        $unreadNotificationsCount = $user->notifications()->unread()->count();

        $quizData = TraineeQuizData::load($user->id);
        $assignmentData = TraineeAssignmentData::load($user->id);

        return view('trainee.dashboard', array_merge(
            compact(
                'user',
                'traineeProfile',
                'totalEnrollments',
                'ongoingEnrollments',
                'completedEnrollments',
                'enrolledCount',
                'certificates',
                'recentEnrollments',
                'notifications',
                'unreadNotificationsCount',
                'overallAttendance',
                'totalSessions',
                'presentCount',
                'attendanceBatches',
                'todayAttendanceRows',
                'today'
            ),
            $quizData,
            $assignmentData
        ));
    }
}
