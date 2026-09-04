@extends('layouts.admin')

@section('title', 'Edit Question')

@section('content')
@php
    $existingOptions = $question->options->values();
    $slotCount = max(4, $existingOptions->count());
    $oldOptions = old('options');
    $oldCorrect = collect(old('correct_options', $existingOptions->filter->is_correct->keys()->all()))
        ->map(fn ($index) => (int) $index)
        ->all();
@endphp

<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit Question</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quizzes</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.show', $quiz) }}">{{ $quiz->title }}</a></li>
            <li class="breadcrumb-item active">Edit Question</li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $quiz->title }}</h5>
                @unless($question->isActive())
                <span class="badge bg-secondary">Inactive</span>
                @endunless
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.quizzes.questions.update', [$quiz, $question]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Part / Section</label>
                        <input type="text" name="part" class="form-control @error('part') is-invalid @enderror"
                               value="{{ old('part', $question->part) }}" placeholder="e.g. Part-I">
                        @error('part')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Question *</label>
                        <textarea name="question_text" class="form-control @error('question_text') is-invalid @enderror" rows="4" required>{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Marks *</label>
                        <input type="number" name="marks" class="form-control @error('marks') is-invalid @enderror"
                               value="{{ old('marks', $question->marks) }}" min="1" max="100" required>
                        @error('marks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <p class="fw-semibold mb-2">Options and answer key</p>
                    <p class="small text-muted mb-3">Tick every correct option. At least two options and one correct answer are required.</p>
                    @error('options')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                    @error('correct_option')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                    @for($i = 0; $i < $slotCount; $i++)
                    @php
                        $option = $existingOptions->get($i);
                        $optionText = is_array($oldOptions) ? ($oldOptions[$i] ?? '') : old('options.'.$i, $option->option_text ?? '');
                        $isCorrect = in_array($i, $oldCorrect, true);
                    @endphp
                    <div class="mb-3">
                        <label class="form-label">Option {{ chr(65 + $i) }}{{ $i < 2 ? ' *' : '' }}</label>
                        <div class="input-group">
                            <input type="text" name="options[{{ $i }}]" class="form-control"
                                   value="{{ $optionText }}" {{ $i < 2 ? 'required' : '' }}>
                            <div class="input-group-text">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="correct_options[]" value="{{ $i }}"
                                           id="correct_option_{{ $i }}" {{ $isCorrect ? 'checked' : '' }}>
                                    <label class="form-check-label" for="correct_option_{{ $i }}">Correct</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Question
                        </button>
                        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
