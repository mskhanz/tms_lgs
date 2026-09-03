<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginSessionTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            activity()
                ->useLog('auth')
                ->withProperties(['email' => $request->email])
                ->log('Failed login attempt');

            throw ValidationException::withMessages([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        }

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('You must verify your email address before logging in. Please check your inbox for the verification link.'),
            ]);
        }

        if (!$user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Your account has been deactivated. Please contact the administrator.'),
            ]);
        }

        $request->session()->regenerate();

        app(LoginSessionTracker::class)->start($user, $request);

        // Redirect based on user type
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->isTrainer()) {
            return redirect()->intended(route('trainer.dashboard'));
        } else {
            return redirect()->intended(route('trainee.dashboard'));
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        app(LoginSessionTracker::class)->end($user, $request);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
