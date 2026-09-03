<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AsyncMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;

        AsyncMail::sendAfterResponse(function () use ($email) {
            Password::sendResetLink(['email' => $email]);
        });

        return back()->with('success', 'If an account exists for that email, a password reset link is being sent. Please check your inbox and spam folder.');
    }
}
