<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingNomination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trainee_id', 'training_batch_id', 'nominated_by', 'organization_id',
        'nomination_reason', 'status', 'approved_by', 'approved_at', 'approval_remarks'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    public function nominatedBy()
    {
        return $this->belongsTo(User::class, 'nominated_by');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function enrollment()
    {
        return $this->hasOne(TrainingEnrollment::class, 'nomination_id');
    }
}
