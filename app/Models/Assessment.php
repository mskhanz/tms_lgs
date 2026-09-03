<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_program_id', 'title', 'description', 'type',
        'total_marks', 'passing_marks', 'duration_minutes',
        'assessment_date', 'status'
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'assessment_date' => 'date',
    ];

    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function results()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
