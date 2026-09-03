<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'title', 'description', 'category', 'type',
        'duration_days', 'duration_hours', 'budget_allocated',
        'objectives', 'target_audience', 'max_participants', 'min_participants',
        'conducting_organization_id', 'status',
        'attendance_enabled', 'min_attendance_percentage',
        'created_by', 'approved_by', 'approved_at', 'approval_remarks'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'budget_allocated' => 'decimal:2',
        'attendance_enabled' => 'boolean',
    ];

    public static function categoryOptions(): array
    {
        return [
            'technical' => 'Technical',
            'leadership' => 'Leadership',
            'management' => 'Management',
            'specialized' => 'Specialized',
            'soft_skills' => 'Soft Skills',
            'mid_career_training' => 'Mid Career Training',
            'pre_service_training' => 'Pre-service Training',
            'pre_promotion_training' => 'Pre-Promotion Training',
            'others' => 'Others',
        ];
    }

    public function categoryLabel(): string
    {
        return self::categoryOptions()[$this->category] ?? ucwords(str_replace('_', ' ', (string) $this->category));
    }

    public function isAttendanceEnabled(): bool
    {
        return (bool) $this->attendance_enabled;
    }

    public function conductingOrganization()
    {
        return $this->belongsTo(Organization::class, 'conducting_organization_id');
    }

    public function batches()
    {
        return $this->hasMany(TrainingBatch::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
