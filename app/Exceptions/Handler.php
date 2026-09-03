<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            return $this->handleExpiredSession($request);
        }

        if ($e instanceof NotFoundHttpException && ! $request->expectsJson()) {
            return response()->view('errors.404', [], 404);
        }

        return parent::render($request, $e);
    }

    private function handleExpiredSession(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Page expired. Please refresh and try again.',
            ], 419);
        }

        $loginRoute = route('login', [], false);

        if ($request->is($loginRoute) || $request->is('login')) {
            return redirect()->route('login')
                ->with('warning', 'Your session expired. Please sign in again.');
        }

        return redirect()->guest(route('login'))
            ->with('warning', 'Your session expired. Please sign in again.');
    }
}
