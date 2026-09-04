<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id', 'title', 'original_name', 'stored_name', 'mime_type', 'file_size',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function displayName(): string
    {
        return $this->title ?: $this->original_name;
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
