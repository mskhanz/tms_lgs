<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_batch_id', 'training_session_type_id', 'title', 'description', 'session_date',
        'start_time', 'end_time', 'venue', 'trainer_id',
        'topics_covered', 'status'
    ];

    protected $casts = [
        'session_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    public function sessionType()
    {
        return $this->belongsTo(TrainingSessionType::class, 'training_session_type_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
