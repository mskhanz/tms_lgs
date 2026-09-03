<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_program_id', 'batch_code', 'start_date', 'end_date',
        'venue', 'venue_address', 'total_seats', 'seats_filled',
        'status', 'attendance_enabled', 'min_attendance_percentage',
        'remarks', 'coordinator_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'attendance_enabled' => 'boolean',
    ];

    public static function statusOptions(): array
    {
        return [
            'scheduled' => 'Scheduled',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'scheduled' => 'info',
            'ongoing' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function seatsAvailable(): int
    {
        return max(0, (int) $this->total_seats - (int) $this->seats_filled);
    }

    public function isAttendanceEnabled(): bool
    {
        if (! $this->attendance_enabled) {
            return false;
        }

        return $this->trainingProgram?->isAttendanceEnabled() ?? true;
    }

    public function isAttendanceActive(): bool
    {
        return $this->isAttendanceEnabled()
            && in_array($this->status, ['scheduled', 'ongoing', 'completed'], true);
    }

    public function effectiveMinAttendancePercentage(): ?int
    {
        return $this->min_attendance_percentage
            ?? $this->trainingProgram?->min_attendance_percentage;
    }

    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'training_batch_trainers')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function nominations()
    {
        return $this->hasMany(TrainingNomination::class);
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
