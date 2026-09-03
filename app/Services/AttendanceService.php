<?php

namespace App\Services;

use App\Models\AttendanceChangeLog;
use App\Models\AttendanceRecord;
use App\Models\TrainingEnrollment;
use App\Models\TrainingSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class AttendanceService
{
    public static function canScheduleSessionOnDate(Carbon|string $sessionDate): bool
    {
        return Carbon::parse($sessionDate)->startOfDay()->gte(Date::today());
    }

    public static function canMarkAttendanceForSession(TrainingSession $session): bool
    {
        if (! $session->session_date) {
            return false;
        }

        return $session->session_date->isSameDay(Date::today());
    }

    public static function recalculateEnrollmentPercentage(TrainingEnrollment $enrollment): void
    {
        $total = $enrollment->attendanceRecords()->count();

        if ($total === 0) {
            $enrollment->update(['attendance_percentage' => 0]);

            return;
        }

        $present = $enrollment->attendanceRecords()
            ->whereIn('status', ['present', 'late'])
            ->count();

        $enrollment->update([
            'attendance_percentage' => round(($present / $total) * 100, 2),
        ]);
    }

    public static function recalculateBatchEnrollments(int $batchId): void
    {
        TrainingEnrollment::where('training_batch_id', $batchId)
            ->each(fn (TrainingEnrollment $enrollment) => self::recalculateEnrollmentPercentage($enrollment));
    }

    public static function saveSessionMarks(TrainingSession $session, array $marks, int $markedBy): void
    {
        if (! self::canMarkAttendanceForSession($session)) {
            throw new \RuntimeException('Attendance can only be marked or updated on the session date. Past dates are locked.');
        }

        foreach ($marks as $enrollmentId => $data) {
            $status = $data['status'] ?? 'absent';
            if (! in_array($status, ['present', 'absent', 'late', 'excused'], true)) {
                continue;
            }

            $enrollment = TrainingEnrollment::where('id', $enrollmentId)
                ->where('training_batch_id', $session->training_batch_id)
                ->first();

            if (! $enrollment) {
                continue;
            }

            $checkInTime = self::resolveCheckInTime($session, $status, $data['check_in_time'] ?? null);
            $existing = AttendanceRecord::where('training_session_id', $session->id)
                ->where('enrollment_id', $enrollment->id)
                ->first();

            $record = AttendanceRecord::updateOrCreate(
                [
                    'training_session_id' => $session->id,
                    'enrollment_id' => $enrollment->id,
                ],
                [
                    'trainee_id' => $enrollment->trainee_id,
                    'status' => $status,
                    'check_in_time' => $checkInTime,
                    'marking_method' => 'manual',
                    'marked_by' => $markedBy,
                    'remarks' => $data['remarks'] ?? null,
                ]
            );

            self::logAttendanceChange($session, $record, $existing, $markedBy);

            self::recalculateEnrollmentPercentage($enrollment);
        }
    }

    private static function logAttendanceChange(
        TrainingSession $session,
        AttendanceRecord $record,
        ?AttendanceRecord $before,
        int $changedBy
    ): void {
        $action = $before ? 'updated' : 'created';

        if ($before
            && $before->status === $record->status
            && (string) $before->check_in_time === (string) $record->check_in_time
            && (string) $before->remarks === (string) $record->remarks) {
            return;
        }

        AttendanceChangeLog::create([
            'attendance_record_id' => $record->id,
            'training_session_id' => $session->id,
            'enrollment_id' => $record->enrollment_id,
            'trainee_id' => $record->trainee_id,
            'changed_by' => $changedBy,
            'session_date' => $session->session_date,
            'action' => $action,
            'old_status' => $before?->status,
            'new_status' => $record->status,
            'old_check_in_time' => $before?->check_in_time,
            'new_check_in_time' => $record->check_in_time,
            'old_remarks' => $before?->remarks,
            'new_remarks' => $record->remarks,
        ]);

        activity()
            ->useLog('attendance')
            ->performedOn($record)
            ->causedBy(User::find($changedBy))
            ->withProperties([
                'session_id' => $session->id,
                'session_date' => $session->session_date?->toDateString(),
                'action' => $action,
                'old_status' => $before?->status,
                'new_status' => $record->status,
                'trainee_id' => $record->trainee_id,
            ])
            ->log('Attendance '.$action.' for trainee on '.$session->session_date?->format('d M Y'));
    }

    private static function resolveCheckInTime(TrainingSession $session, string $status, ?string $checkInTime): ?Carbon
    {
        if (! in_array($status, ['present', 'late'], true)) {
            return null;
        }

        if ($checkInTime) {
            return Carbon::parse($session->session_date->format('Y-m-d').' '.$checkInTime);
        }

        if ($session->start_time) {
            return Carbon::parse($session->session_date->format('Y-m-d').' '.Carbon::parse($session->start_time)->format('H:i:s'));
        }

        return now();
    }
}
