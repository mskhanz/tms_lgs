<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TraineeQualification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trainee_profile_id', 'degree_id', 'institute', 'country_id',
        'subject_id', 'passing_year', 'percentage_marks'
    ];

    public function traineeProfile()
    {
        return $this->belongsTo(TraineeProfile::class);
    }

    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getMarksPercentageAttribute(): ?string
    {
        return $this->attributes['percentage_marks'] ?? null;
    }
}
