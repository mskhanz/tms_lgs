<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class AssignmentFileStorage
{
    public const ALLOWED_MIMES = 'pdf,doc,docx,jpg,jpeg,png,webp';

    public const MAX_KB = 10240;

    public static function directory(): string
    {
        $dir = public_path('assignment_files');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    public static function store(UploadedFile $file, string $prefix = 'file'): array
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $original = $file->getClientOriginalName();
        $mime = $file->getClientMimeType();
        $size = (int) $file->getSize();
        $stored = $prefix.'_'.now()->format('YmdHis').'_'.Str::random(8).'.'.$extension;
        $file->move(self::directory(), $stored);

        return [
            'original_name' => $original,
            'stored_name' => $stored,
            'mime_type' => $mime,
            'file_size' => $size,
        ];
    }

    public static function delete(?string $storedName): void
    {
        if (! $storedName) {
            return;
        }

        $path = public_path('assignment_files/'.$storedName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
