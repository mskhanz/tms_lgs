<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'certificate_number', 'enrollment_id', 'trainee_id', 'training_batch_id',
        'certificate_file', 'qr_code', 'issue_date', 'remarks',
        'issued_by', 'approved_by', 'approved_at', 'status'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class);
    }

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
