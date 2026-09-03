@extends('layouts.admin')

@section('title', 'Quiz Result')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-bar-chart me-2"></i>Quiz Result</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('trainee.quizzes.index') }}">Quizzes</a></li>
            <li class="breadcrumb-item active">Result</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="mb-3">
                    @if($attempt->passed)
                    <i class="bi bi-trophy-fill text-success" style="font-size:4rem;"></i>
                    @else
                    <i class="bi bi-x-circle-fill text-danger" style="font-size:4rem;"></i>
                    @endif
                </div>
                <h2>{{ $attempt->quiz->title }}</h2>
                <h3 class="mt-3 {{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                    {{ $attempt->passed ? 'PASSED' : 'FAILED' }}
                </h3>
                <div class="row mt-4">
                    <div class="col-4"><h4>{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</h4><small class="text-muted">Correct</small></div>
                    <div class="col-4"><h4>{{ $attempt->percentage }}%</h4><small class="text-muted">Score</small></div>
                    <div class="col-4"><h4>{{ $attempt->quiz->passing_percentage }}%</h4><small class="text-muted">Required</small></div>
                </div>
                <p class="text-muted mt-3 mb-0">Submitted: {{ $attempt->submitted_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>
</div>

@if($showAnswers)
<div class="card">
    <div class="card-header"><h5 class="mb-0">Answer Review</h5></div>
    <div class="card-body">
        @php $qNum = 0; @endphp
        @foreach($questions as $question)
        @php
            $qNum++;
            $answer = $attempt->answers->firstWhere('question_id', $question->id);
            $shuffledOptions = $attempt->getShuffledOptionsFor($question);
            $correct = $question->options->firstWhere('is_correct', true);
        @endphp
        <div class="border rounded p-3 mb-3 {{ $answer?->is_correct ? 'border-success bg-light' : 'border-danger' }}">
            <p class="fw-semibold">Q{{ $qNum }}. {{ $question->question_text }}</p>
            @foreach($shuffledOptions as $option)
            <div class="ms-3 small
                {{ $option->is_correct ? 'text-success fw-bold' : '' }}
                {{ $answer && $answer->selected_option_id == $option->id && !$option->is_correct ? 'text-danger' : '' }}">
                @if($option->is_correct) ✓ @elseif($answer && $answer->selected_option_id == $option->id) ✗ @else ○ @endif
                {{ $option->option_text }}
                @if($answer && $answer->selected_option_id == $option->id) <em>(Your answer)</em> @endif
            </div>
            @endforeach
            @if(!$answer?->is_correct && $correct)
            <div class="ms-3 small text-success mt-1">Correct: {{ $correct->option_text }}</div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="mt-4">
    <a href="{{ route('trainee.quizzes.index') }}" class="btn btn-primary"><i class="bi bi-arrow-left me-1"></i>Back to Quizzes</a>
</div>
@endsection
