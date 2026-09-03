<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class TraineePhotoStorage
{
    public static function store(UploadedFile $file): string
    {
        $directory = public_path('user_photos');

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new \RuntimeException('Unable to create photo upload directory on the server.');
        }

        if (! is_writable($directory)) {
            throw new \RuntimeException('Photo upload directory is not writable on the server.');
        }

        $extension = self::resolveExtension($file);
        $filename = 'trainee_' . now()->format('YmdHis') . '_' . Str::random(10) . '.' . $extension;

        $file->move($directory, $filename);

        @chmod($directory . DIRECTORY_SEPARATOR . $filename, 0644);

        return $filename;
    }

    private static function resolveExtension(UploadedFile $file): string
    {
        $imageInfo = @getimagesize($file->getPathname());

        $mimeToExtension = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if ($imageInfo !== false && isset($mimeToExtension[$imageInfo['mime']])) {
            return $mimeToExtension[$imageInfo['mime']];
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: '');

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return $extension === 'jpeg' ? 'jpg' : $extension;
        }

        return 'jpg';
    }
}
