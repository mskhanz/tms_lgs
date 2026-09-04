<?php

namespace App\Support;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TraineeQuizData
{
    /**
     * @return array{
     *     availableQuizzes: \Illuminate\Support\Collection,
     *     openQuizzesCount: int,
     *     quizAttempts: \Illuminate\Support\Collection,
     *     quizLoadError: string|null
     * }
     */
    public static function load(?int $userId = null): array
    {
        $userId ??= Auth::id();

        $empty = [
            'availableQuizzes' => collect(),
            'openQuizzesCount' => 0,
            'quizAttempts' => collect(),
            'quizLoadError' => null,
        ];

        if (! $userId) {
            return $empty;
        }

        try {
            if (! SchemaCache::hasTable('quizzes')) {
                throw new \RuntimeException('Quiz tables are missing. Run: php artisan migrate --force');
            }

            $availableQuizzes = Quiz::withCount(['activeQuestions as questions_count'])
                ->with(['trainingProgram', 'trainingBatch.trainingProgram'])
                ->activeForTrainees();

            if (SchemaCache::hasColumn('quizzes', 'assign_to')) {
                $availableQuizzes->assignedToTrainee($userId);
            }

            $availableQuizzes = $availableQuizzes->latest()->get();

            return [
                'availableQuizzes' => $availableQuizzes,
                'openQuizzesCount' => $availableQuizzes->filter(
                    fn (Quiz $quiz) => $quiz->isAvailable()
                )->count(),
                'quizAttempts' => QuizAttempt::where('user_id', $userId)
                    ->latest()
                    ->get()
                    ->groupBy('quiz_id'),
                'quizLoadError' => null,
            ];
        } catch (\Throwable $e) {
            Log::error('Trainee quiz data load failed: '.$e->getMessage(), [
                'user_id' => $userId,
            ]);

            return [
                ...$empty,
                'quizLoadError' => config('app.debug')
                    ? $e->getMessage()
                    : 'Quiz module is not ready on the server. Please ask the administrator to upload latest files and run migrations.',
            ];
        }
    }

    /**
     * Dashboard: only open quizzes the trainee can still take (not closed / not fully submitted).
     *
     * @return array{
     *     availableQuizzes: \Illuminate\Support\Collection,
     *     openQuizzesCount: int,
     *     quizAttempts: \Illuminate\Support\Collection,
     *     quizLoadError: string|null
     * }
     */
    public static function forDashboard(?int $userId = null): array
    {
        $data = self::load($userId);
        $attempts = $data['quizAttempts'];

        $availableQuizzes = $data['availableQuizzes']->filter(function (Quiz $quiz) use ($attempts) {
            if (! $quiz->isAvailable()) {
                return false;
            }

            $userAttempts = $attempts->get($quiz->id, collect());
            $completed = $userAttempts->where('status', 'completed')->count();
            $inProgress = $userAttempts->firstWhere('status', 'in_progress');

            if ($inProgress) {
                return true;
            }

            return $completed < (int) $quiz->max_attempts;
        })->values();

        return [
            'availableQuizzes' => $availableQuizzes,
            'openQuizzesCount' => $availableQuizzes->count(),
            'quizAttempts' => $attempts,
            'quizLoadError' => $data['quizLoadError'],
        ];
    }

    /**
     * @return array{
     *     quizzes: \Illuminate\Support\Collection,
     *     attempts: \Illuminate\Support\Collection,
     *     loadError: string|null
     * }
     */
    public static function forQuizIndex(?int $userId = null): array
    {
        $data = self::load($userId);

        return [
            'quizzes' => $data['availableQuizzes'],
            'attempts' => $data['quizAttempts'],
            'loadError' => $data['quizLoadError'],
        ];
    }
}
