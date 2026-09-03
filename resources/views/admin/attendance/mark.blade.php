@extends('layouts.admin')

@section('title', 'Mark Attendance')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-check2-square me-2"></i>Mark Attendance</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.batches.attendance.show', $batch) }}">{{ $batch->batch_code }}</a></li>
                    <li class="breadcrumb-item active">{{ $session->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Session</div>
                <div class="fw-semibold">{{ $session->title }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Date</div>
                <div class="fw-semibold">{{ $session->session_date?->format('d M Y') }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Program</div>
                <div class="fw-semibold">{{ $batch->trainingProgram->title ?? 'N/A' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Session time</div>
                <div class="fw-semibold">
                    {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }}
                    –
                    {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(! $canEdit)
<div class="alert alert-warning" role="alert">
    <i class="bi bi-lock me-2"></i>
    This session is on a previous date. Attendance is <strong>read-only</strong> and cannot be updated.
    @if($session->session_date?->isFuture())
        Attendance can be marked on {{ $session->session_date->format('d M Y') }}.
    @endif
</div>
@endif

<form method="POST" action="{{ route('admin.batches.attendance.sessions.save', [$batch, $session]) }}">
    @csrf
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Trainees</h5>
            <span class="text-muted small">{{ $batch->enrollments->count() }} enrolled</span>
        </div>
        <div class="card-body p-0">
            @if($batch->enrollments->count())
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Trainee</th>
                            <th>Check-in time</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batch->enrollments as $enrollment)
                            @php
                                $existing = $existingMarks->get($enrollment->id);
                                $currentStatus = old('marks.'.$enrollment->id.'.status', $existing->status ?? 'present');
                                $defaultCheckIn = old(
                                    'marks.'.$enrollment->id.'.check_in_time',
                                    $existing?->check_in_time?->format('H:i')
                                        ?? \Carbon\Carbon::parse($session->start_time)->format('H:i')
                                );
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $enrollment->trainee->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $enrollment->trainee->traineeProfile->cnic_no ?? $enrollment->trainee->email ?? '' }}</small>
                                </td>
                                <td style="min-width: 140px;">
                                    <input type="time"
                                           name="marks[{{ $enrollment->id }}][check_in_time]"
                                           class="form-control form-control-sm attendance-check-in"
                                           value="{{ $defaultCheckIn }}"
                                           data-default="{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}"
                                           {{ ! $canEdit ? 'readonly disabled' : '' }}>
                                </td>
                                <td style="min-width: 160px;">
                                    <select name="marks[{{ $enrollment->id }}][status]"
                                            class="form-select form-select-sm attendance-status"
                                            {{ ! $canEdit ? 'disabled' : '' }}
                                            @if($canEdit) required @endif>
                                        @foreach(['present' => 'Present', 'absent' => 'Absent', 'late' => 'Late', 'excused' => 'Excused'] as $value => $label)
                                            <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if(! $canEdit)
                                        <input type="hidden" name="marks[{{ $enrollment->id }}][status]" value="{{ $currentStatus }}">
                                    @endif
                                </td>
                                <td>
                                    <input type="text"
                                           name="marks[{{ $enrollment->id }}][remarks]"
                                           class="form-control form-control-sm"
                                           value="{{ old('marks.'.$enrollment->id.'.remarks', $existing->remarks ?? '') }}"
                                           placeholder="Optional"
                                           {{ ! $canEdit ? 'readonly disabled' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted text-center py-4 mb-0">No trainees enrolled in this batch.</p>
            @endif
        </div>
        @if($batch->enrollments->count() && $canEdit)
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Save attendance
            </button>
            <a href="{{ route('admin.batches.attendance.show', $batch) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
        @elseif($batch->enrollments->count())
        <div class="card-footer">
            <a href="{{ route('admin.batches.attendance.show', $batch) }}" class="btn btn-outline-secondary">Back</a>
        </div>
        @endif
    </div>
</form>

@if($changeLogs->count())
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Attendance change log</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Trainee</th>
                        <th>Action</th>
                        <th>Change</th>
                        <th>By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($changeLogs as $log)
                    <tr>
                        <td class="text-nowrap">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $log->trainee->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-{{ $log->action === 'created' ? 'success' : 'warning text-dark' }}">{{ ucfirst($log->action) }}</span></td>
                        <td class="small">
                            Status: {{ $log->old_status ? ucfirst($log->old_status) : '—' }} → {{ ucfirst($log->new_status ?? '—') }}<br>
                            @if($log->old_check_in_time || $log->new_check_in_time)
                                Time: {{ $log->old_check_in_time?->format('h:i A') ?? '—' }} → {{ $log->new_check_in_time?->format('h:i A') ?? '—' }}<br>
                            @endif
                            @if($log->old_remarks !== $log->new_remarks)
                                Remarks: {{ $log->old_remarks ?: '—' }} → {{ $log->new_remarks ?: '—' }}
                            @endif
                        </td>
                        <td>{{ $log->changedBy->name ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if($canEdit)
<script>
document.querySelectorAll('.attendance-status').forEach(function (select) {
    const row = select.closest('tr');
    const timeInput = row?.querySelector('.attendance-check-in');

    function syncTimingField() {
        if (!timeInput) return;
        const needsTime = ['present', 'late'].includes(select.value);
        timeInput.disabled = !needsTime;
        if (!needsTime) {
            timeInput.value = '';
        } else if (!timeInput.value) {
            timeInput.value = timeInput.dataset.default || '';
        }
    }

    select.addEventListener('change', syncTimingField);
    syncTimingField();
});
</script>
@endif
@endpush
