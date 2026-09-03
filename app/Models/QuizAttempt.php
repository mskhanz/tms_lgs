<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'user_id', 'started_at', 'submitted_at', 'total_questions',
        'correct_answers', 'score', 'percentage', 'passed', 'status',
        'question_order', 'option_orders',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'passed' => 'boolean',
        'question_order' => 'array',
        'option_orders' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'attempt_id');
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isExpired(): bool
    {
        if (! $this->isInProgress() || ! $this->quiz->duration_minutes) {
            return false;
        }

        return $this->started_at->addMinutes($this->quiz->duration_minutes)->isPast();
    }

    public function getOrderedQuestions()
    {
        $questions = QuizQuestion::with('options')
            ->whereIn('id', $this->question_order)
            ->get()
            ->keyBy('id');

        return collect($this->question_order)
            ->map(fn ($id) => $questions->get($id))
            ->filter();
    }

    public function getShuffledOptionsFor(QuizQuestion $question)
    {
        $optionIds = $this->option_orders[(string) $question->id]
            ?? $this->option_orders[$question->id]
            ?? $question->options->pluck('id')->all();

        $options = $question->options->keyBy('id');

        return collect($optionIds)->map(fn ($id) => $options->get($id))->filter();
    }
}
