<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmailNotification;
use App\Support\AsyncMail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'registration_training_id',
        'photo',
        'is_active',
        'profile_completed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'profile_completed' => 'boolean',
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function traineeProfile()
    {
        return $this->hasOne(TraineeProfile::class, 'user_id');
    }

    public function registrationTraining()
    {
        return $this->belongsTo(RegistrationTraining::class, 'registration_training_id');
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class, 'trainee_id');
    }

    public function nominations()
    {
        return $this->hasMany(TrainingNomination::class, 'trainee_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'trainee_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function loginSessions()
    {
        return $this->hasMany(LoginSession::class);
    }

    // Helper methods
    public function roleNames()
    {
        if (! $this->relationLoaded('roles')) {
            $this->load('roles');
        }

        return $this->roles->pluck('name');
    }

    public function hasRole($role)
    {
        $names = collect(is_array($role) ? $role : [$role]);

        return $this->roleNames()->intersect($names)->isNotEmpty();
    }

    public function hasAnyRole($roles)
    {
        return $this->hasRole($roles);
    }

    public function isTrainee()
    {
        return $this->user_type === 'trainee';
    }

    public function isTrainer()
    {
        return $this->user_type === 'trainer';
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function photoUrl(): ?string
    {
        if ($this->photo && file_exists(public_path('user_photos/'.$this->photo))) {
            return asset('user_photos/'.$this->photo);
        }

        $profile = $this->relationLoaded('traineeProfile') ? $this->traineeProfile : $this->traineeProfile()->first();

        if ($profile && $profile->file_picture && file_exists(public_path('trainee_pictures/'.$profile->file_picture))) {
            return asset('trainee_pictures/'.$profile->file_picture);
        }

        return null;
    }

    public function sendEmailVerificationNotification(): void
    {
        AsyncMail::sendAfterResponse(function () {
            $this->notify(new VerifyEmailNotification);
        });
    }
}

