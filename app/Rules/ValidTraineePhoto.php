<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ValidTraineePhoto implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('Please upload or capture your trainee photo.');

            return;
        }

        if (! $value->isValid()) {
            $fail($this->uploadErrorMessage($value->getError()));

            return;
        }

        if ($value->getSize() > 5 * 1024 * 1024) {
            $fail('Photo must be 5 MB or smaller.');

            return;
        }

        $imageInfo = @getimagesize($value->getPathname());

        if ($imageInfo === false) {
            $fail('Please upload a valid photo (JPG, PNG, or WEBP). Camera photos are supported.');

            return;
        }

        $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];

        if (! in_array($imageInfo[2], $allowedTypes, true)) {
            $fail('Photo must be JPG, PNG, or WEBP format.');
        }
    }

    private function uploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Photo is too large. Maximum size is 5 MB.',
            UPLOAD_ERR_PARTIAL => 'Photo upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_FILE => 'Please upload or capture your trainee photo.',
            default => 'Photo upload failed. Please try again or choose a smaller image.',
        };
    }
}
