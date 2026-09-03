@php
    $showCard = $showCard ?? true;
@endphp

@if($showCard)
<div class="card trainee-enrollment-card {{ $cardClass ?? '' }}">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Enrollment Details</h5>
    </div>
    <div class="card-body">
@else
<div class="trainee-enrollment-sheet">
@endif
        @forelse($enrollments as $enrollment)
            @php
                $batch = $enrollment->trainingBatch;
                $program = $batch?->trainingProgram;
            @endphp
            <div class="enrollment-item {{ ! $loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="fw-semibold text-dark">
                        {{ $program->title ?? 'Training not set' }}
                    </div>
                    <span class="badge enrollment-status-badge
                        {{ $enrollment->status === 'completed' ? 'bg-success' : '' }}
                        {{ $enrollment->status === 'in_progress' ? 'bg-primary' : '' }}
                        {{ $enrollment->status === 'enrolled' ? 'bg-warning text-dark' : '' }}
                        {{ in_array($enrollment->status, ['dropped', 'failed']) ? 'bg-danger' : '' }}">
                        {{ ucwords(str_replace('_', ' ', $enrollment->status)) }}
                    </span>
                </div>
                <div class="enrollment-meta small text-muted">
                    <div class="mb-1">
                        <span class="text-secondary">Batch:</span>
                        <strong class="text-body">{{ $batch->batch_code ?? 'N/A' }}</strong>
                        @if($program?->code)
                            <span class="ms-1">({{ $program->code }})</span>
                        @endif
                    </div>
                    @if($batch?->start_date && $batch?->end_date)
                    <div class="mb-1">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $batch->start_date->format('M d, Y') }} – {{ $batch->end_date->format('M d, Y') }}
                    </div>
                    @endif
                    @if($batch?->venue)
                    <div class="mb-1">
                        <i class="bi bi-geo-alt me-1"></i>{{ $batch->venue }}
                    </div>
                    @endif
                    @if($batch)
                    <div class="mb-1">
                        <span class="text-secondary">Batch status:</span>
                        <span class="badge bg-{{ $batch->statusBadge() }}">{{ $batch->statusLabel() }}</span>
                    </div>
                    @endif
                    <div class="mb-1">
                        <span class="text-secondary">Enrolled:</span>
                        {{ $enrollment->enrollment_date?->format('M d, Y') ?? 'N/A' }}
                    </div>
                    @if($batch?->isAttendanceEnabled())
                    <div>
                        <span class="text-secondary">Attendance:</span>
                        <strong class="text-body">{{ number_format((float) $enrollment->attendance_percentage, 1) }}%</strong>
                        @if($batch->effectiveMinAttendancePercentage())
                            <span class="ms-1">(required {{ $batch->effectiveMinAttendancePercentage() }}%)</span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted text-center mb-0 py-2 small">No enrollments yet.</p>
        @endforelse
@if($showCard)
    </div>
</div>
@else
</div>
@endif
