<?php

namespace App\Http\Controllers;

use App\Models\LoginSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::user()->load(['roles', 'trainer']);

        if ($user->isTrainee()) {
            return redirect()->route('trainee.profile.show');
        }

        $lastLogin = $user->loginSessions()->latest('logged_in_at')->first();
        $currentSession = $user->loginSessions()
            ->whereNull('logged_out_at')
            ->latest('logged_in_at')
            ->first();

        return view('account.profile', compact('user', 'lastLogin', 'currentSession'));
    }

    public function edit()
    {
        $user = Auth::user()->load(['roles', 'trainer']);

        if ($user->isTrainee()) {
            return redirect()->route('trainee.profile.edit');
        }

        return view('account.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->isTrainee()) {
            return redirect()->route('trainee.profile.edit');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoDir = public_path('user_photos');
            if (! is_dir($photoDir)) {
                mkdir($photoDir, 0755, true);
            }

            if ($user->photo && file_exists($photoDir.DIRECTORY_SEPARATOR.$user->photo)) {
                unlink($photoDir.DIRECTORY_SEPARATOR.$user->photo);
            }

            $file = $request->file('photo');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('user_photos'), $filename);
            $validated['photo'] = $filename;
        }

        $user->update($validated);

        activity()
            ->useLog('profile')
            ->performedOn($user)
            ->causedBy($user)
            ->log('Updated account profile');

        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }

    public function editPassword()
    {
        return view('account.password', [
            'user' => Auth::user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8), 'different:current_password'],
        ]);

        $user = $request->user();
        $user->update([
            'password' => $request->password,
        ]);

        $currentSessionId = $request->session()->getId();
        LoginSession::query()
            ->where('user_id', $user->id)
            ->whereNull('logged_out_at')
            ->where('session_id', '!=', $currentSessionId)
            ->update([
                'logged_out_at' => now(),
                'logout_reason' => 'forced',
            ]);

        Auth::logoutOtherDevices($request->password);

        activity()
            ->useLog('auth')
            ->causedBy($user)
            ->log('Changed password');

        return back()->with('success', 'Password changed successfully. Other devices have been signed out.');
    }
}
