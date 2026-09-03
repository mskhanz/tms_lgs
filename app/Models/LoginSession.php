<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'logged_in_at',
        'last_activity_at',
        'logged_out_at',
        'logout_reason',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNull('logged_out_at');
    }

    public function scopeOnline(Builder $query): Builder
    {
        return $query->open()
            ->where('last_activity_at', '>=', now()->subMinutes($this->onlineMinutes()));
    }

    public function isOnline(): bool
    {
        if ($this->logged_out_at) {
            return false;
        }

        return $this->last_activity_at
            && $this->last_activity_at->gte(now()->subMinutes($this->onlineMinutes()));
    }

    public function durationSeconds(): int
    {
        $end = $this->logged_out_at ?? now();

        if (! $this->logged_in_at) {
            return 0;
        }

        return max(0, $this->logged_in_at->diffInSeconds($end));
    }

    public function durationLabel(): string
    {
        $seconds = $this->durationSeconds();
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remain = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%dh %02dm %02ds', $hours, $minutes, $remain);
        }

        if ($minutes > 0) {
            return sprintf('%dm %02ds', $minutes, $remain);
        }

        return sprintf('%ds', $remain);
    }

    public function statusLabel(): string
    {
        if ($this->isOnline()) {
            return 'Online';
        }

        if (! $this->logged_out_at) {
            return 'Idle';
        }

        return match ($this->logout_reason) {
            'expired' => 'Expired',
            'forced' => 'Ended',
            default => 'Logged out',
        };
    }

    public function statusBadgeClass(): string
    {
        if ($this->isOnline()) {
            return 'success';
        }

        if (! $this->logged_out_at) {
            return 'warning';
        }

        return 'secondary';
    }

    private function onlineMinutes(): int
    {
        return max(1, (int) config('activity.online_minutes', 5));
    }
}
