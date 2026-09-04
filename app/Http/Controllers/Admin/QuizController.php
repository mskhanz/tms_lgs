<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\QuizAttempt;
use App\Models\TrainingProgram;
use App\Models\TrainingBatch;
use App\Models\User;
use App\Services\QuizMsqImporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::withCount([
            'questions',
            'attempts',
            'attempts as completed_attempts_count' => fn ($q) => $q->where('status', 'completed'),
        ])->with(['creator', 'trainingProgram', 'trainingBatch.trainingProgram']);

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $quizzes = $query->latest()->paginate(15);

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('admin.quizzes.create', $this->assignmentFormData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuiz($request);
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active');
        $validated = $this->normalizeAssignment($validated);

        $quiz = Quiz::create($validated);

        activity()
            ->performedOn($quiz)
            ->causedBy(Auth::user())
            ->log('Quiz created');

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Quiz created. Add questions below.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['questions.options', 'creator', 'trainingProgram', 'trainingBatch.trainingProgram']);
        $attempts = QuizAttempt::with('user')
            ->where('quiz_id', $quiz->id)
            ->where('status', 'completed')
            ->latest('submitted_at')
            ->paginate(20);

        return view('admin.quizzes.show', compact('quiz', 'attempts'));
    }

    public function results(Quiz $quiz)
    {
        return view('admin.quizzes.results', $this->buildResultsReport($quiz));
    }

    public function downloadResultsPdf(Quiz $quiz)
    {
        $data = $this->buildResultsReport($quiz);
        $data['logoSrc'] = $this->logoDataUri();

        $filename = 'quiz-results-'.Str::slug($quiz->title).'.pdf';

        return Pdf::loadView('admin.quizzes.results-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->download($filename);
    }

    public function edit(Quiz $quiz)
    {
        return view('admin.quizzes.edit', array_merge(
            compact('quiz'),
            $this->assignmentFormData()
        ));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $this->validateQuiz($request);
        $validated['is_active'] = $request->boolean('is_active');
        $validated = $this->normalizeAssignment($validated);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }

    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $payload = $this->validateQuestionPayload($request);

        $sortOrder = $quiz->questions()->max('sort_order') + 1;

        $question = $quiz->questions()->create([
            'part' => $payload['part'],
            'question_text' => $payload['question_text'],
            'marks' => $payload['marks'],
            'sort_order' => $sortOrder,
        ]);

        $this->syncQuestionOptions($question, $payload['options'], $payload['correct_indexes']);

        return back()->with('success', 'Question added successfully.');
    }

    public function editQuestion(Quiz $quiz, QuizQuestion $question)
    {
        abort_unless($question->quiz_id === $quiz->id, 404);
        $question->load('options');

        return view('admin.quizzes.edit-question', compact('quiz', 'question'));
    }

    public function updateQuestion(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        abort_unless($question->quiz_id === $quiz->id, 404);

        $payload = $this->validateQuestionPayload($request);

        DB::transaction(function () use ($question, $payload) {
            $question->update([
                'part' => $payload['part'],
                'question_text' => $payload['question_text'],
                'marks' => $payload['marks'],
            ]);

            $this->syncQuestionOptions($question, $payload['options'], $payload['correct_indexes']);
        });

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Question updated successfully.');
    }

    public function destroyQuestion(Quiz $quiz, QuizQuestion $question)
    {
        abort_unless($question->quiz_id === $quiz->id, 404);
        $question->options()->delete();
        $question->delete();

        return back()->with('success', 'Question deleted successfully.');
    }

    public function toggleQuestionStatus(Quiz $quiz, QuizQuestion $question)
    {
        abort_unless($question->quiz_id === $quiz->id, 404);

        $nowActive = ! $question->isActive();
        $question->update(['is_active' => $nowActive]);

        return back()->with('success', $nowActive
            ? 'Question activated successfully.'
            : 'Question deactivated successfully.');
    }

    public function downloadQuestionTemplate()
    {
        $filename = 'quiz-msq-import-template.xlsx';

        return response(QuizMsqImporter::templateBinary(), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function importQuestions(Request $request, Quiz $quiz, QuizMsqImporter $importer)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ], [
            'file.required' => 'Please choose an Excel (.xlsx) file to import.',
            'file.max' => 'The Excel file must be 5 MB or smaller.',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension !== 'xlsx') {
            return back()->with('error', 'Please upload an Excel file with the .xlsx extension.');
        }

        try {
            $result = $importer->import($quiz, $file, $request->boolean('replace_existing'));
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        activity()
            ->performedOn($quiz)
            ->causedBy(Auth::user())
            ->withProperties($result)
            ->log('Imported quiz MSQs from Excel');

        $message = $result['imported'].' MSQ'.($result['imported'] === 1 ? '' : 's').' imported from Excel.';
        if ($result['replaced']) {
            $message = 'Existing questions were replaced. '.$message;
        }

        return back()->with('success', $message);
    }

    public function toggleStatus(Quiz $quiz)
    {
        $quiz->update(['is_active' => ! $quiz->is_active]);

        return back()->with('success', 'Quiz status updated.');
    }

    private function buildResultsReport(Quiz $quiz): array
    {
        $quiz->load(['trainingProgram', 'trainingBatch.trainingProgram']);

        $assignedIds = $quiz->assignedTraineeIds();

        $attempted = QuizAttempt::with(['user.traineeProfile.organization'])
            ->where('quiz_id', $quiz->id)
            ->where('status', 'completed')
            ->orderByDesc('percentage')
            ->orderByDesc('score')
            ->orderByDesc('submitted_at')
            ->get()
            ->unique('user_id')
            ->values();

        $attemptedIds = $attempted->pluck('user_id')->all();

        $inProgressIds = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $notAttempted = User::query()
            ->with(['traineeProfile.organization'])
            ->whereIn('id', $assignedIds)
            ->whereNotIn('id', $attemptedIds)
            ->orderBy('name')
            ->get();

        $stats = [
            'assigned' => count($assignedIds),
            'attempted' => $attempted->count(),
            'not_attempted' => $notAttempted->count(),
            'passed' => $attempted->where('passed', true)->count(),
            'failed' => $attempted->where('passed', false)->count(),
            'average' => $attempted->count() ? round((float) $attempted->avg('percentage'), 1) : 0,
        ];

        return compact('quiz', 'attempted', 'notAttempted', 'inProgressIds', 'stats');
    }

    private function logoDataUri(): ?string
    {
        $path = public_path('images/kp-logo.png');

        if (! is_file($path)) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode((string) file_get_contents($path));
    }

    private function validateQuestionPayload(Request $request): array
    {
        $validated = $request->validate([
            'part' => 'nullable|string|max:255',
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1|max:100',
            'options' => 'required|array|min:2',
            'options.*' => 'nullable|string',
            'correct_option' => 'nullable|integer|min:0',
            'correct_options' => 'nullable|array',
            'correct_options.*' => 'integer|min:0',
        ]);

        $options = [];
        foreach ($validated['options'] as $index => $text) {
            $text = trim((string) $text);
            if ($text !== '') {
                $options[(int) $index] = $text;
            }
        }

        if (count($options) < 2) {
            throw ValidationException::withMessages([
                'options' => 'At least two options are required.',
            ]);
        }

        $correctIndexes = collect($validated['correct_options'] ?? [])
            ->map(fn ($index) => (int) $index)
            ->unique()
            ->values();

        if ($correctIndexes->isEmpty() && array_key_exists('correct_option', $validated) && $validated['correct_option'] !== null) {
            $correctIndexes = collect([(int) $validated['correct_option']]);
        }

        $correctIndexes = $correctIndexes->filter(fn ($index) => isset($options[$index]))->values();

        if ($correctIndexes->isEmpty()) {
            throw ValidationException::withMessages([
                'correct_option' => 'Select at least one correct option.',
            ]);
        }

        return [
            'part' => $validated['part'] ?: null,
            'question_text' => $validated['question_text'],
            'marks' => $validated['marks'],
            'options' => $options,
            'correct_indexes' => $correctIndexes->all(),
        ];
    }

    /**
     * @param  array<int, string>  $options
     * @param  list<int>  $correctIndexes
     */
    private function syncQuestionOptions(QuizQuestion $question, array $options, array $correctIndexes): void
    {
        $existing = $question->options()->orderBy('sort_order')->get()->keyBy('sort_order');
        $keptIds = [];

        foreach ($options as $index => $text) {
            $attributes = [
                'option_text' => $text,
                'is_correct' => in_array((int) $index, $correctIndexes, true),
                'sort_order' => (int) $index,
            ];

            $option = $existing->get($index);
            if ($option) {
                $option->update($attributes);
            } else {
                $option = $question->options()->create($attributes);
            }

            $keptIds[] = $option->id;
        }

        if ($keptIds !== []) {
            $question->options()->whereNotIn('id', $keptIds)->delete();
        }
    }

    private function validateQuiz(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1|max:600',
            'passing_percentage' => 'required|integer|min:1|max:100',
            'max_attempts' => 'required|integer|min:1|max:10',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_results' => 'boolean',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
            'assign_to' => 'required|in:program,batch',
            'training_program_id' => 'required_if:assign_to,program|nullable|exists:training_programs,id',
            'training_batch_id' => 'required_if:assign_to,batch|nullable|exists:training_batches,id',
        ]) + [
            'shuffle_questions' => $request->boolean('shuffle_questions', true),
            'shuffle_options' => $request->boolean('shuffle_options', true),
            'show_results' => $request->boolean('show_results', true),
        ];
    }

    private function assignmentFormData(): array
    {
        return [
            'programs' => TrainingProgram::orderBy('title')->get(['id', 'code', 'title']),
            'batches' => TrainingBatch::with('trainingProgram:id,title,code')
                ->orderByDesc('start_date')
                ->get(['id', 'training_program_id', 'batch_code', 'start_date', 'end_date', 'status']),
        ];
    }

    private function normalizeAssignment(array $validated): array
    {
        if (($validated['assign_to'] ?? null) === 'program') {
            $validated['training_batch_id'] = null;
        }

        if (($validated['assign_to'] ?? null) === 'batch') {
            $batch = TrainingBatch::find($validated['training_batch_id'] ?? null);
            $validated['training_program_id'] = $batch?->training_program_id;
        }

        return $validated;
    }
}
