<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assessment_id', 'enrollment_id', 'trainee_id', 'obtained_marks',
        'percentage', 'grade', 'result', 'feedback',
        'evaluated_by', 'evaluated_at', 'status',
        'approved_by', 'approved_at'
    ];

    protected $casts = [
        'obtained_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'evaluated_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class);
    }

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
