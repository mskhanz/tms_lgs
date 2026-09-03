<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'cnic', 'email', 'phone',
        'qualifications', 'expertise', 'experience', 'years_of_experience',
        'organization', 'designation', 'profile_picture', 'cv_file',
        'status', 'empanelment_date', 'empanelment_expiry',
        'approved_by', 'approved_at', 'approval_remarks'
    ];

    protected $casts = [
        'empanelment_date' => 'date',
        'empanelment_expiry' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batches()
    {
        return $this->belongsToMany(TrainingBatch::class, 'training_batch_trainers')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
