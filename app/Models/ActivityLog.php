<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_name', 'description', 'user_id', 'subject_type', 'subject_id',
        'causer_type', 'causer_id', 'properties', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }
}
