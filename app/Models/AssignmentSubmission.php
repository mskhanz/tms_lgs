<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id', 'user_id', 'written_response', 'status',
        'submitted_at', 'marks', 'admin_feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'marks' => 'decimal:2',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(AssignmentSubmissionFile::class, 'submission_id');
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isLate(): bool
    {
        if (! $this->isSubmitted() || ! $this->submitted_at) {
            return false;
        }

        $dueAt = $this->assignment?->due_at;

        return $dueAt && $this->submitted_at->gt($dueAt);
    }

    public function statusLabel(): string
    {
        if ($this->isSubmitted()) {
            return $this->isLate() ? 'Late' : 'Submitted';
        }

        return 'Draft';
    }
}
