<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'duration_minutes', 'passing_percentage',
        'max_attempts', 'shuffle_questions', 'shuffle_options', 'is_active',
        'show_results', 'available_from', 'available_until', 'created_by',
        'assign_to', 'training_program_id', 'training_batch_id',
    ];

    protected $casts = [
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'is_active' => 'boolean',
        'show_results' => 'boolean',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    public function activeQuestions()
    {
        return $this->questions()->active();
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    public function assignmentLabel(): string
    {
        if ($this->assign_to === 'program') {
            return 'Program: '.($this->trainingProgram->title ?? 'Not set');
        }

        if ($this->assign_to === 'batch') {
            $batch = $this->trainingBatch;
            $program = $batch?->trainingProgram?->title ?? $this->trainingProgram->title ?? '';

            return trim('Batch: '.($batch->batch_code ?? 'Not set').($program ? ' — '.$program : ''));
        }

        return 'Not assigned';
    }

    public static function traineeEnrollmentStatuses(): array
    {
        return ['enrolled', 'in_progress', 'completed'];
    }

    public function assignedTraineeIds(): array
    {
        $statuses = self::traineeEnrollmentStatuses();

        if ($this->assign_to === 'batch' && $this->training_batch_id) {
            return TrainingEnrollment::query()
                ->where('training_batch_id', $this->training_batch_id)
                ->whereIn('status', $statuses)
                ->pluck('trainee_id')
                ->unique()
                ->values()
                ->all();
        }

        if ($this->assign_to === 'program' && $this->training_program_id) {
            return TrainingEnrollment::query()
                ->whereIn('status', $statuses)
                ->whereHas('trainingBatch', function ($query) {
                    $query->where('training_program_id', $this->training_program_id);
                })
                ->pluck('trainee_id')
                ->unique()
                ->values()
                ->all();
        }

        return [];
    }

    public function isAssignedToTrainee(?int $userId): bool
    {
        if (! $userId || ! $this->assign_to) {
            return false;
        }

        $enrollmentQuery = TrainingEnrollment::query()
            ->where('trainee_id', $userId)
            ->whereIn('status', self::traineeEnrollmentStatuses());

        if ($this->assign_to === 'batch' && $this->training_batch_id) {
            return $enrollmentQuery->where('training_batch_id', $this->training_batch_id)->exists();
        }

        if ($this->assign_to === 'program' && $this->training_program_id) {
            return $enrollmentQuery
                ->whereHas('trainingBatch', function ($query) {
                    $query->where('training_program_id', $this->training_program_id);
                })
                ->exists();
        }

        return false;
    }

    public function scopeAssignedToTrainee($query, int $userId)
    {
        $statuses = self::traineeEnrollmentStatuses();

        return $query->where(function ($outer) use ($userId, $statuses) {
            $outer->where(function ($q) use ($userId, $statuses) {
                $q->where('assign_to', 'program')
                    ->whereNotNull('training_program_id')
                    ->whereIn('training_program_id', function ($sub) use ($userId, $statuses) {
                        $sub->select('training_batches.training_program_id')
                            ->from('training_enrollments')
                            ->join('training_batches', 'training_batches.id', '=', 'training_enrollments.training_batch_id')
                            ->where('training_enrollments.trainee_id', $userId)
                            ->whereIn('training_enrollments.status', $statuses)
                            ->whereNull('training_enrollments.deleted_at')
                            ->whereNull('training_batches.deleted_at');
                    });
            })->orWhere(function ($q) use ($userId, $statuses) {
                $q->where('assign_to', 'batch')
                    ->whereNotNull('training_batch_id')
                    ->whereIn('training_batch_id', function ($sub) use ($userId, $statuses) {
                        $sub->select('training_batch_id')
                            ->from('training_enrollments')
                            ->where('trainee_id', $userId)
                            ->whereIn('status', $statuses)
                            ->whereNull('deleted_at');
                    });
            });
        });
    }

    public function isAvailable(): bool
    {
        return $this->traineeStatus() === 'open';
    }

    public function traineeStatus(): string
    {
        if (! $this->is_active) {
            return 'inactive';
        }

        $questionCount = $this->questions_count
            ?? $this->active_questions_count
            ?? $this->activeQuestions()->count();

        if ($questionCount < 1) {
            return 'no_questions';
        }

        if ($this->available_from && now()->lt($this->available_from)) {
            return 'scheduled';
        }

        if ($this->available_until && now()->gt($this->available_until)) {
            return 'closed';
        }

        return 'open';
    }

    public function traineeStatusLabel(): string
    {
        return match ($this->traineeStatus()) {
            'open' => 'Open',
            'scheduled' => 'Opens ' . $this->available_from->format('M d, h:i A'),
            'closed' => 'Closed',
            'no_questions' => 'No questions yet',
            default => 'Inactive',
        };
    }

    public function scopeActiveForTrainees($query)
    {
        return $query
            ->where('is_active', true)
            ->has('activeQuestions');
    }

    public function totalMarks(): int
    {
        return (int) $this->activeQuestions()->sum('marks');
    }
}
