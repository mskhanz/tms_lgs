<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingEnrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trainee_id', 'training_batch_id', 'nomination_id', 'enrolled_by',
        'enrollment_date', 'status', 'completion_date',
        'attendance_percentage', 'assessment_score', 'remarks'
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'completion_date' => 'date',
        'attendance_percentage' => 'decimal:2',
        'assessment_score' => 'decimal:2',
    ];

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    public function nomination()
    {
        return $this->belongsTo(TrainingNomination::class);
    }

    public function enrolledBy()
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'enrollment_id');
    }

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class, 'enrollment_id');
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'enrollment_id');
    }
}
