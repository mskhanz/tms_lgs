<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'data', 'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function actionUrl(): string
    {
        if (! empty($this->data['url'])) {
            return $this->data['url'];
        }

        return match ($this->type) {
            'enrollment' => route('trainee.dashboard'),
            'assignment' => ! empty($this->data['assignment_id'])
                ? route('trainee.assignments.show', $this->data['assignment_id'])
                : route('trainee.assignments.index'),
            default => route('notifications.index'),
        };
    }

    public function icon(): string
    {
        return $this->data['icon'] ?? match ($this->type) {
            'enrollment' => 'journal-check',
            'assignment' => 'file-earmark-text',
            default => 'info-circle',
        };
    }

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}
