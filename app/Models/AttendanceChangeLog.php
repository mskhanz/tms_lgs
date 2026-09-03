<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_record_id',
        'training_session_id',
        'enrollment_id',
        'trainee_id',
        'changed_by',
        'session_date',
        'action',
        'old_status',
        'new_status',
        'old_check_in_time',
        'new_check_in_time',
        'old_remarks',
        'new_remarks',
    ];

    protected $casts = [
        'session_date' => 'date',
        'old_check_in_time' => 'datetime',
        'new_check_in_time' => 'datetime',
    ];

    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class);
    }

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
