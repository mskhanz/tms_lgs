<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function qualifications()
    {
        return $this->hasMany(TraineeQualification::class);
    }
}
