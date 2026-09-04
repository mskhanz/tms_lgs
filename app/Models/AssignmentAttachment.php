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

    public function extension(): string
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION) ?: '');
    }

    public function isImage(): bool
    {
        $mime = (string) $this->mime_type;
        if (str_starts_with($mime, 'image/')) {
            return true;
        }

        return in_array($this->extension(), ['jpg', 'jpeg', 'png', 'webp'], true);
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf' || $this->extension() === 'pdf';
    }

    public function isPreviewable(): bool
    {
        return $this->isImage() || $this->isPdf();
    }

    public function previewKind(): string
    {
        if ($this->isImage()) {
            return 'image';
        }
        if ($this->isPdf()) {
            return 'pdf';
        }

        return 'other';
    }
}
