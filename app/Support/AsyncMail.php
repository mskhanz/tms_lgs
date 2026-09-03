<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Log;

class AsyncMail
{
    /**
     * Send mail after the HTTP response so the user is not kept waiting on SMTP.
     */
    public static function sendAfterResponse(Closure $callback): void
    {
        dispatch(static function () use ($callback) {
            try {
                $callback();
            } catch (\Throwable $e) {
                Log::error('Background mail failed: '.$e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        })->afterResponse();
    }
}
