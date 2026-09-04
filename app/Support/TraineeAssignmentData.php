<?php

namespace App\Support;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TraineeAssignmentData
{
    /**
     * @return array{
     *     availableAssignments: \Illuminate\Support\Collection,
     *     openAssignmentsCount: int,
     *     assignmentSubmissions: \Illuminate\Support\Collection,
     *     assignmentLoadError: string|null
     * }
     */
    public static function load(?int $userId = null): array
    {
        $userId ??= Auth::id();

        $empty = [
            'availableAssignments' => collect(),
            'openAssignmentsCount' => 0,
            'assignmentSubmissions' => collect(),
            'assignmentLoadError' => null,
        ];

        if (! $userId) {
            return $empty;
        }

        try {
            if (! SchemaCache::hasTable('assignments')) {
                throw new \RuntimeException('Assignment tables are missing. Run: php artisan migrate --force');
            }

            $availableAssignments = Assignment::withCount('attachments')
                ->with(['trainingProgram', 'trainingBatch.trainingProgram', 'attachments'])
                ->activeForTrainees()
                ->assignedToTrainee($userId)
                ->latest()
                ->get();

            $submissions = AssignmentSubmission::where('user_id', $userId)
                ->whereIn('assignment_id', $availableAssignments->pluck('id'))
                ->get()
                ->keyBy('assignment_id');

            $openCount = $availableAssignments->filter(function (Assignment $assignment) use ($submissions) {
                if (! $assignment->isAvailable()) {
                    return false;
                }
                $submission = $submissions->get($assignment->id);

                return ! $submission || ! $submission->isSubmitted();
            })->count();

            return [
                'availableAssignments' => $availableAssignments,
                'openAssignmentsCount' => $openCount,
                'assignmentSubmissions' => $submissions,
                'assignmentLoadError' => null,
            ];
        } catch (\Throwable $e) {
            Log::error('Trainee assignment data load failed: '.$e->getMessage(), [
                'user_id' => $userId,
            ]);

            return [
                ...$empty,
                'assignmentLoadError' => config('app.debug')
                    ? $e->getMessage()
                    : 'Assignment module is not ready on the server.',
            ];
        }
    }

    /**
     * @return array{
     *     assignments: \Illuminate\Support\Collection,
     *     submissions: \Illuminate\Support\Collection,
     *     loadError: string|null
     * }
     */
    public static function forIndex(?int $userId = null): array
    {
        $data = self::load($userId);

        return [
            'assignments' => $data['availableAssignments'],
            'submissions' => $data['assignmentSubmissions'],
            'loadError' => $data['assignmentLoadError'],
        ];
    }
}
