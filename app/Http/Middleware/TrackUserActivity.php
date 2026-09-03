<?php

namespace App\Http\Middleware;

use App\Services\LoginSessionTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function __construct(private LoginSessionTracker $tracker)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $user->loadMissing(['roles', 'traineeProfile']);

            if (! $request->routeIs('trainee.quizzes.save')) {
                try {
                    $this->tracker->touch($user, $request);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return $next($request);
    }
}
