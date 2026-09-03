<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationConfirmation;
use App\Models\User;
use App\Models\Role;
use App\Models\RegistrationTraining;
use App\Rules\ValidTraineePhoto;
use App\Support\AsyncMail;
use App\Support\TraineePhotoStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        $trainings = RegistrationTraining::active()->get();

        return view('auth.register', compact('trainings'));
    }

    public function store(Request $request)
    {
        if (empty($request->all()) && (int) $request->server('CONTENT_LENGTH') > 0) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'photo' => 'Photo is too large for the server upload limit. Please use a smaller image (max 5 MB).',
                ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'photo' => ['required', 'file', new ValidTraineePhoto],
            'registration_training_id' => ['required', 'exists:registration_trainings,id', function ($attribute, $value, $fail) {
                if (! RegistrationTraining::where('id', $value)->where('is_active', true)->exists()) {
                    $fail('The selected training program is not available for registration.');
                }
            }],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $training = RegistrationTraining::active()->findOrFail($request->registration_training_id);

        $plainPassword = $request->password;

        try {
            $photoFilename = TraineePhotoStorage::store($request->file('photo'));
        } catch (\Throwable $e) {
            Log::error('Trainee photo upload failed: ' . $e->getMessage());

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'photo' => 'Unable to save your photo on the server. Please contact support or try a smaller JPG image.',
                ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'user_type' => 'trainee',
            'registration_training_id' => $training->id,
            'photo' => $photoFilename,
            'is_active' => true,
            'profile_completed' => false,
        ]);

        // Assign trainee role
        $traineeRole = Role::where('name', 'trainee')->first();
        $user->roles()->attach($traineeRole->id);

        activity()
            ->useLog('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->log('Registered');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        AsyncMail::sendAfterResponse(function () use ($user, $plainPassword, $verificationUrl, $training) {
            Mail::to($user->email)->send(
                new RegistrationConfirmation($user, $plainPassword, $verificationUrl, $training)
            );
        });

        Auth::logout();

        return redirect()->route('home')
            ->with('success', 'Registration successful! Your confirmation email is being sent — please check your inbox shortly.');
    }
}
