<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Support\TraineeQuizData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $quizData = TraineeQuizData::forQuizIndex($user->id);

        return view('trainee.quizzes.index', [
            'quizzes' => $quizData['quizzes'],
            'attempts' => $quizData['attempts'],
            'loadError' => $quizData['loadError'],
        ]);
    }

    public function start(Quiz $quiz)
    {
        $user = Auth::user();

        if (! $quiz->isAvailable() || $quiz->questions()->count() === 0) {
            return redirect()->route('trainee.quizzes.index')
                ->with('error', 'This quiz is not available.');
        }

        if (! $quiz->isAssignedToTrainee($user->id)) {
            return redirect()->route('trainee.quizzes.index')
                ->with('error', 'You can only take quizzes assigned to a program or batch you are enrolled in.');
        }

        $completedAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        if ($completedAttempts >= $quiz->max_attempts) {
            return redirect()->route('trainee.quizzes.index')
                ->with('error', 'You have used all allowed attempts for this quiz.');
        }

        $inProgress = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if ($inProgress) {
            if ($inProgress->isExpired()) {
                $inProgress->update(['status' => 'expired']);
            } else {
                return redirect()->route('trainee.quizzes.take', $inProgress);
            }
        }

        $attempt = $this->createShuffledAttempt($quiz, $user->id);

        activity()
            ->useLog('quiz')
            ->performedOn($quiz)
            ->causedBy($user)
            ->log('Started quiz: '.$quiz->title);

        return redirect()->route('trainee.quizzes.take', $attempt);
    }

    public function take(QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

        if ($attempt->status === 'completed') {
            return redirect()->route('trainee.quizzes.result', $attempt);
        }

        if ($attempt->isExpired()) {
            $attempt->update(['status' => 'expired']);

            return redirect()->route('trainee.quizzes.index')
                ->with('error', 'Quiz time has expired.');
        }

        $attempt->load(['quiz', 'answers']);
        $questions = $attempt->getOrderedQuestions();
        $savedAnswers = $attempt->answers->pluck('selected_option_id', 'question_id');

        $endsAt = $attempt->quiz->duration_minutes
            ? $attempt->started_at->copy()->addMinutes($attempt->quiz->duration_minutes)
            : null;

        return view('trainee.quizzes.take', compact('attempt', 'questions', 'endsAt', 'savedAnswers'));
    }

    public function saveProgress(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

        if ($attempt->status !== 'in_progress') {
            return response()->json(['ok' => false, 'message' => 'This quiz is no longer in progress.'], 422);
        }

        if ($attempt->isExpired()) {
            $attempt->update(['status' => 'expired']);

            return response()->json(['ok' => false, 'message' => 'Quiz time has expired.'], 422);
        }

        $request->validate([
            'question_id' => 'nullable|integer',
            'option_id' => 'nullable|integer',
            'answers' => 'nullable|array',
            'answers.*' => 'nullable|integer',
        ]);

        $saved = 0;

        if ($request->filled('question_id') && $request->filled('option_id')) {
            if ($this->persistAnswer($attempt, (int) $request->question_id, (int) $request->option_id)) {
                $saved++;
            }
        }

        foreach ((array) $request->input('answers', []) as $questionId => $optionId) {
            if ($optionId === null || $optionId === '') {
                continue;
            }

            if ($this->persistAnswer($attempt, (int) $questionId, (int) $optionId)) {
                $saved++;
            }
        }

        return response()->json([
            'ok' => true,
            'saved' => $saved,
            'saved_at' => now()->format('h:i:s A'),
        ]);
    }

    public function submit(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('trainee.quizzes.result', $attempt);
        }

        if ($attempt->isExpired()) {
            $attempt->update(['status' => 'expired']);

            return redirect()->route('trainee.quizzes.index')
                ->with('error', 'Quiz time has expired before submission.');
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|exists:quiz_options,id',
        ]);

        $attempt->load('answers');
        $unanswered = collect($attempt->question_order)->filter(function ($questionId) use ($request, $attempt) {
            $selectedId = $request->input("answers.{$questionId}")
                ?: optional($attempt->answers->firstWhere('question_id', (int) $questionId))->selected_option_id;

            return empty($selectedId);
        });

        if ($unanswered->isNotEmpty()) {
            return back()->with('error', 'Please answer all remaining questions before submitting the quiz.');
        }

        DB::transaction(function () use ($request, $attempt) {
            $attempt->load(['quiz', 'answers']);
            $correct = 0;
            $score = 0;
            $questions = QuizQuestion::where('quiz_id', $attempt->quiz_id)->get()->keyBy('id');

            foreach ($attempt->question_order as $questionId) {
                $question = $questions->get($questionId);
                if (! $question) {
                    continue;
                }

                $selectedId = $request->input("answers.{$questionId}")
                    ?: optional($attempt->answers->firstWhere('question_id', (int) $questionId))->selected_option_id;
                $selectedOption = $selectedId ? QuizOption::find($selectedId) : null;
                $isCorrect = $selectedOption
                    && $selectedOption->question_id === $question->id
                    && $selectedOption->is_correct;

                if ($isCorrect) {
                    $correct++;
                    $score += $question->marks;
                }

                QuizAttemptAnswer::updateOrCreate(
                    ['attempt_id' => $attempt->id, 'question_id' => $question->id],
                    ['selected_option_id' => $selectedId, 'is_correct' => $isCorrect]
                );
            }

            $totalQuestions = count($attempt->question_order);
            $totalMarks = $questions->sum('marks') ?: $totalQuestions;
            $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 2) : 0;

            $attempt->update([
                'submitted_at' => now(),
                'status' => 'completed',
                'total_questions' => $totalQuestions,
                'correct_answers' => $correct,
                'score' => $score,
                'percentage' => $percentage,
                'passed' => $percentage >= $attempt->quiz->passing_percentage,
            ]);
        });

        $attempt->refresh()->loadMissing('quiz');

        activity()
            ->useLog('quiz')
            ->performedOn($attempt->quiz)
            ->causedBy(Auth::user())
            ->withProperties([
                'score' => $attempt->score,
                'percentage' => $attempt->percentage,
            ])
            ->log('Submitted quiz: '.$attempt->quiz->title);

        return redirect()->route('trainee.quizzes.result', $attempt)
            ->with('success', 'Quiz submitted successfully.');
    }

    public function result(QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

        if ($attempt->status !== 'completed') {
            return redirect()->route('trainee.quizzes.take', $attempt);
        }

        $attempt->load(['quiz', 'answers.selectedOption', 'answers.question']);

        $questions = $attempt->getOrderedQuestions();
        $showAnswers = $attempt->quiz->show_results;

        return view('trainee.quizzes.result', compact('attempt', 'questions', 'showAnswers'));
    }

    private function createShuffledAttempt(Quiz $quiz, int $userId): QuizAttempt
    {
        $questionIds = $quiz->questions()->orderBy('sort_order')->pluck('id')->toArray();

        if ($quiz->shuffle_questions) {
            shuffle($questionIds);
        }

        $optionOrders = [];
        $questions = QuizQuestion::with('options')->whereIn('id', $questionIds)->get()->keyBy('id');

        foreach ($questionIds as $questionId) {
            $optionIds = $questions->get($questionId)?->options->pluck('id')->toArray() ?? [];

            if ($quiz->shuffle_options && count($optionIds) > 1) {
                shuffle($optionIds);
            }

            $optionOrders[$questionId] = $optionIds;
        }

        return QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $userId,
            'started_at' => now(),
            'total_questions' => count($questionIds),
            'status' => 'in_progress',
            'question_order' => $questionIds,
            'option_orders' => $optionOrders,
        ]);
    }

    private function persistAnswer(QuizAttempt $attempt, int $questionId, int $optionId): bool
    {
        if (! in_array($questionId, array_map('intval', $attempt->question_order ?? []), true)) {
            return false;
        }

        $option = QuizOption::where('id', $optionId)
            ->where('question_id', $questionId)
            ->first();

        if (! $option) {
            return false;
        }

        QuizAttemptAnswer::updateOrCreate(
            ['attempt_id' => $attempt->id, 'question_id' => $questionId],
            [
                'selected_option_id' => $option->id,
                'is_correct' => (bool) $option->is_correct,
            ]
        );

        return true;
    }

    private function authorizeAttempt(QuizAttempt $attempt): void
    {
        abort_unless($attempt->user_id === Auth::id(), 403);
    }
}
