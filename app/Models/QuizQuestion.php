<?php

namespace App\Models;

use App\Support\SchemaCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'part', 'question_text', 'marks', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isActive(): bool
    {
        if (! array_key_exists('is_active', $this->attributes)) {
            return true;
        }

        return (bool) $this->attributes['is_active'];
    }

    public function scopeActive($query)
    {
        if (! SchemaCache::hasColumn('quiz_questions', 'is_active')) {
            return $query;
        }

        return $query->where('is_active', true);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(QuizOption::class, 'question_id')->orderBy('sort_order');
    }

    public function correctOption()
    {
        return $this->hasOne(QuizOption::class, 'question_id')->where('is_correct', true);
    }
}
