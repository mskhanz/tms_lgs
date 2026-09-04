@php
    $submission = $submissions->get($assignment->id);
    $status = $assignment->traineeStatus();
    $isSubmitted = $submission?->isSubmitted();
    if ($isSubmitted) {
        $badgeLabel = $submission->isLate() ? 'Late' : 'Submitted';
        $badgeClass = $submission->isLate() ? 'bg-warning text-dark' : 'bg-success text-white';
    } elseif ($submission) {
        $badgeLabel = 'Draft';
        $badgeClass = 'bg-info text-white';
    } else {
        $badgeLabel = match ($status) {
            'open' => 'Not submitted',
            'scheduled' => $assignment->traineeStatusLabel(),
            'closed' => 'Closed',
            default => 'Inactive',
        };
        $badgeClass = match ($status) {
            'open' => 'bg-secondary text-white',
            'scheduled' => 'bg-warning text-dark',
            default => 'bg-light text-dark',
        };
    }
@endphp

<div class="{{ $cardClass ?? 'card h-100' }}">
    <div class="{{ isset($cardClass) ? 'p-3 d-flex flex-column h-100' : 'card-body d-flex flex-column' }}">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
            <h5 class="{{ isset($cardClass) ? 'h6 fw-semibold' : 'card-title' }} mb-0">{{ $assignment->title }}</h5>
            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
        </div>

        @if($assignment->instructions)
        <p class="card-text text-muted small flex-grow-1">{{ Str::limit(strip_tags($assignment->instructions), 100) }}</p>
        @endif

        <ul class="list-unstyled small mb-3">
            <li><i class="bi bi-mortarboard me-1"></i>{{ $assignment->assignmentLabel() }}</li>
            <li><i class="bi bi-trophy me-1"></i>Marks: {{ number_format((float) $assignment->total_marks, 0) }}</li>
            <li><i class="bi bi-paperclip me-1"></i>{{ $assignment->attachments_count ?? $assignment->attachments->count() }} file(s)</li>
            <li>
                <i class="bi bi-calendar-event me-1"></i>
                Due: {{ $assignment->due_at?->format('d M Y, h:i A') ?? 'No due date' }}
                @if(! $isSubmitted && $assignment->due_at)
                    <span class="asg-countdown"
                          data-asg-due="{{ $assignment->due_at->toIso8601String() }}"
                          title="Time remaining until due date">—</span>
                @endif
            </li>
        </ul>

        @if($status === 'open' || $submission)
        <a href="{{ route('trainee.assignments.show', $assignment) }}" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i>
            {{ $isSubmitted ? 'View / Update' : ($submission ? 'Continue' : 'Open Assignment') }}
        </a>
        @elseif($status === 'scheduled')
        <button class="btn btn-outline-warning w-100" disabled>Not open yet</button>
        @else
        <button class="btn btn-secondary w-100" disabled>Unavailable</button>
        @endif
    </div>
</div>
