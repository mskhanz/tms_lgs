<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'type', 'parent_id', 'district_id', 'address', 'contact_number',
        'email', 'is_active', 'created_by', 'approved_by', 'approved_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function traineeProfiles()
    {
        return $this->hasMany(TraineeProfile::class);
    }

    public function trainingPrograms()
    {
        return $this->hasMany(TrainingProgram::class, 'conducting_organization_id');
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
