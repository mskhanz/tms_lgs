@php
    $userAttempts = $attempts->get($quiz->id, collect());
    $completed = $userAttempts->where('status', 'completed')->count();
    $inProgress = $userAttempts->firstWhere('status', 'in_progress');
    $best = $userAttempts->where('status', 'completed')->max('percentage');
    $canTake = $completed < $quiz->max_attempts;
    $status = $quiz->traineeStatus();
    $statusClass = match ($status) {
        'open' => 'bg-success',
        'scheduled' => 'bg-warning text-dark',
        'closed' => 'bg-secondary',
        default => 'bg-light text-dark',
    };
@endphp

<div class="{{ $cardClass ?? 'card h-100' }}">
    <div class="{{ isset($cardClass) ? 'p-3 d-flex flex-column h-100' : 'card-body d-flex flex-column' }}">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
            <h5 class="{{ isset($cardClass) ? 'h6 fw-semibold' : 'card-title' }} mb-0">{{ $quiz->title }}</h5>
            <span class="badge {{ $statusClass }}">{{ $quiz->traineeStatusLabel() }}</span>
        </div>

        @if($quiz->description)
        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($quiz->description, 100) }}</p>
        @endif

        <ul class="list-unstyled small mb-3">
            <li><i class="bi bi-mortarboard me-1"></i>{{ $quiz->assignmentLabel() }}</li>
            <li><i class="bi bi-question-circle me-1"></i>{{ $quiz->questions_count }} Questions</li>
            <li><i class="bi bi-clock me-1"></i>{{ $quiz->duration_minutes ? $quiz->duration_minutes.' minutes' : 'No time limit' }}</li>
            <li><i class="bi bi-trophy me-1"></i>Pass: {{ $quiz->passing_percentage }}%</li>
            <li><i class="bi bi-arrow-repeat me-1"></i>Attempts: {{ $completed }}/{{ $quiz->max_attempts }}</li>
            @if($quiz->available_from || $quiz->available_until)
            <li><i class="bi bi-calendar-range me-1"></i>
                @if($quiz->available_from && $quiz->available_until)
                    {{ $quiz->available_from->format('M d, h:i A') }} - {{ $quiz->available_until->format('M d, h:i A') }}
                @elseif($quiz->available_from)
                    From {{ $quiz->available_from->format('M d, h:i A') }}
                @else
                    Until {{ $quiz->available_until->format('M d, h:i A') }}
                @endif
            </li>
            @endif
            @if($best !== null)
            <li><i class="bi bi-star me-1"></i>Best: {{ $best }}%</li>
            @endif
        </ul>

        @if($inProgress && $status === 'open')
        <a href="{{ route('trainee.quizzes.take', $inProgress) }}" class="btn btn-warning w-100">
            <i class="bi bi-play-fill me-1"></i>Continue Quiz
        </a>
        @elseif($canTake && $status === 'open')
        <a href="{{ route('trainee.quizzes.start', $quiz) }}" class="btn btn-primary w-100">
            <i class="bi bi-play-circle me-1"></i>{{ $completed > 0 ? 'Retake Quiz' : 'Start Quiz' }}
        </a>
        @elseif($status === 'scheduled')
        <button class="btn btn-outline-warning w-100" disabled>Not open yet</button>
        @elseif($status === 'closed')
        @php $last = $userAttempts->where('status', 'completed')->first(); @endphp
        @if($last)
        <a href="{{ route('trainee.quizzes.result', $last) }}" class="btn btn-outline-success w-100">View Result</a>
        @else
        <button class="btn btn-secondary w-100" disabled>Quiz closed</button>
        @endif
        @elseif(! $canTake)
        @php $last = $userAttempts->where('status', 'completed')->first(); @endphp
        @if($last)
        <a href="{{ route('trainee.quizzes.result', $last) }}" class="btn btn-outline-success w-100">View Result</a>
        @else
        <button class="btn btn-secondary w-100" disabled>No attempts left</button>
        @endif
        @else
        <button class="btn btn-secondary w-100" disabled>Unavailable</button>
        @endif
    </div>
</div>
