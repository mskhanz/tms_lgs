<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmissionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id', 'original_name', 'stored_name', 'mime_type', 'file_size',
    ];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    public function absolutePath(): string
    {
        return public_path('assignment_files/'.$this->stored_name);
    }

    public function existsOnDisk(): bool
    {
        return is_file($this->absolutePath());
    }
}
