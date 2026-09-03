<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id', 'enrollment_id', 'trainee_id', 'status',
        'check_in_time', 'check_out_time', 'marking_method', 'marked_by', 'remarks'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

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

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function changeLogs()
    {
        return $this->hasMany(AttendanceChangeLog::class);
    }
}
