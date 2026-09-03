<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function tehsils()
    {
        return $this->hasMany(Tehsil::class);
    }

    public function traineeProfiles()
    {
        return $this->hasMany(TraineeProfile::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}
