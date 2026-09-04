@extends('layouts.admin')

@section('title', $quiz->title)

@section('content')
<div class="page-header">
    <h1><i class="bi bi-clipboard-check me-2"></i>{{ $quiz->title }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quizzes</a></li>
            <li class="breadcrumb-item active">Manage</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" style="white-space: pre-line;">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@error('file')
<div class="alert alert-danger alert-dismissible fade show">{{ $message }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@enderror

@php
    $activeQuestionCount = $quiz->questions->filter(fn ($q) => $q->isActive())->count();
    $inactiveQuestionCount = $quiz->questions->count() - $activeQuestionCount;
@endphp

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-primary">{{ $activeQuestionCount }}</h3><small class="text-muted">Active Questions</small>@if($inactiveQuestionCount)<div class="small text-secondary mt-1">{{ $inactiveQuestionCount }} inactive</div>@endif</div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-success">{{ $quiz->totalMarks() }}</h3><small class="text-muted">Total Marks</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-info">{{ $quiz->duration_minutes ?? '∞' }}</h3><small class="text-muted">Minutes</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-warning">{{ $quiz->passing_percentage }}%</h3><small class="text-muted">Passing Score</small></div></div></div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit Settings</a>
    <a href="{{ route('admin.quizzes.results', $quiz) }}" class="btn btn-outline-success"><i class="bi bi-bar-chart me-1"></i>Results</a>
    <form action="{{ route('admin.quizzes.toggle-status', $quiz) }}" method="POST">@csrf
        <button class="btn btn-outline-{{ $quiz->is_active ? 'warning' : 'success' }}">
            {{ $quiz->is_active ? 'Deactivate' : 'Activate' }}
        </button>
    </form>
</div>

<div class="alert {{ $quiz->assign_to ? 'alert-info' : 'alert-warning' }}">
    <strong>Assigned to:</strong>
    {{ $quiz->assignmentLabel() }}
    @if(! $quiz->assign_to)
        <div class="small mt-1">Assign this quiz to a program or batch so enrolled trainees can take it.</div>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Questions ({{ $quiz->questions->count() }})</h5>
                <span class="badge bg-success">Shuffle: {{ $quiz->shuffle_questions ? 'ON' : 'OFF' }} / Options: {{ $quiz->shuffle_options ? 'ON' : 'OFF' }}</span>
            </div>
            <div class="card-body">
                @forelse($quiz->questions as $index => $question)
                <div class="border rounded p-3 mb-3 {{ $question->isActive() ? '' : 'bg-light border-secondary' }}">
                    <div class="d-flex justify-content-between gap-3">
                        <div class="{{ $question->isActive() ? '' : 'opacity-75' }}">
                            @if($question->part)<span class="badge bg-light text-dark mb-2">{{ $question->part }}</span>@endif
                            @unless($question->isActive())
                            <span class="badge bg-secondary mb-2">Inactive</span>
                            @endunless
                            <p class="mb-2"><strong>Q{{ $index + 1 }}.</strong> {{ $question->question_text }}</p>
                            <ul class="list-unstyled ms-3 mb-0">
                                @foreach($question->options as $opt)
                                <li class="{{ $opt->is_correct ? 'text-success fw-bold' : '' }}">
                                    {{ $opt->is_correct ? '✓' : '○' }} {{ $opt->option_text }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}" class="btn btn-sm btn-outline-primary" title="Edit this question">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.quizzes.questions.toggle-status', [$quiz, $question]) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-{{ $question->isActive() ? 'warning' : 'success' }} w-100" title="{{ $question->isActive() ? 'Deactivate' : 'Activate' }} this question">
                                    <i class="bi bi-{{ $question->isActive() ? 'pause-circle' : 'play-circle' }}"></i>
                                    {{ $question->isActive() ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" onsubmit="return confirm('Delete this question?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger w-100" title="Delete this question"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3">No questions yet. Import an Excel file or add one below.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-file-earmark-excel me-2"></i>Import MSQs (Excel)</h5></div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Upload an <strong>.xlsx</strong> file with MSQs and the answer key.
                    Required columns: <code>Question</code>, <code>Option A</code>, <code>Option B</code>, <code>Answer Key</code>.
                    Optional: <code>Part</code>, <code>Option C</code>, <code>Option D</code>, <code>Marks</code>.
                    The answer key can also be a second sheet named <strong>Answer Key</strong> with <code>Question No</code> and <code>Answer Key</code>.
                </p>
                <a href="{{ route('admin.quizzes.questions.template') }}" class="btn btn-outline-success btn-sm mb-3">
                    <i class="bi bi-download me-1"></i>Download template
                </a>
                <form method="POST" action="{{ route('admin.quizzes.questions.import', $quiz) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="file" class="form-control" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="replace_existing" value="1" id="replace_existing">
                        <label class="form-check-label" for="replace_existing">Replace existing questions</label>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-upload me-1"></i>Import from Excel
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Add Question</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.quizzes.questions.store', $quiz) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Part / Section</label>
                        <input type="text" name="part" class="form-control" placeholder="e.g. Part-I">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Question *</label>
                        <textarea name="question_text" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Marks</label>
                        <input type="number" name="marks" class="form-control" value="1" min="1">
                    </div>
                    @for($i = 0; $i < 4; $i++)
                    <div class="mb-2">
                        <label class="form-label">Option {{ chr(65 + $i) }}</label>
                        <input type="text" name="options[]" class="form-control" {{ $i < 2 ? 'required' : '' }}>
                    </div>
                    @endfor
                    <div class="mb-3">
                        <label class="form-label">Correct Option *</label>
                        <select name="correct_option" class="form-select" required>
                            <option value="0">A</option>
                            <option value="1">B</option>
                            <option value="2">C</option>
                            <option value="3">D</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Question</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if($attempts->count())
<div class="card mt-4">
    <div class="card-header"><h5 class="mb-0">Trainee Attempts</h5></div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead><tr><th>Trainee</th><th>Score</th><th>%</th><th>Result</th><th>Submitted</th></tr></thead>
            <tbody>
                @foreach($attempts as $attempt)
                <tr>
                    <td>{{ $attempt->user->name }}</td>
                    <td>{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</td>
                    <td>{{ $attempt->percentage }}%</td>
                    <td><span class="badge bg-{{ $attempt->passed ? 'success' : 'danger' }}">{{ $attempt->passed ? 'Passed' : 'Failed' }}</span></td>
                    <td>{{ $attempt->submitted_at?->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $attempts->links() }}</div>
</div>
@endif
@endsection
