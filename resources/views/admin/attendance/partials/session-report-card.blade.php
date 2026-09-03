@php
    $statusLabels = [
        'present' => 'Present',
        'absent' => 'Absent',
        'late' => 'Late',
        'excused' => 'Excused',
        'not_marked' => 'Not marked',
    ];
    $statusBadges = [
        'present' => 'success',
        'absent' => 'danger',
        'late' => 'warning',
        'excused' => 'info',
        'not_marked' => 'secondary',
    ];
@endphp

<div class="card mb-4 session-report-block">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>{{ $sessionData->session->title }}</h5>
            <small class="text-muted">
                {{ $sessionData->program->title ?? 'N/A' }}
                · {{ $sessionData->batch->batch_code ?? 'N/A' }}
                · {{ $sessionData->session->session_date?->format('d M Y') }}
                · {{ \Carbon\Carbon::parse($sessionData->session->start_time)->format('h:i A') }}
                – {{ \Carbon\Carbon::parse($sessionData->session->end_time)->format('h:i A') }}
            </small>
        </div>
        @if($showActions ?? true)
        <div class="d-flex gap-2">
            @if($sessionData->batch?->isAttendanceActive())
            <a href="{{ route('admin.batches.attendance.sessions.mark', [$sessionData->batch, $sessionData->session]) }}" class="btn btn-sm btn-primary">
                Mark attendance
            </a>
            @endif
        </div>
        @endif
    </div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-auto"><span class="badge bg-success">Present: {{ $sessionData->statusCounts['present'] }}</span></div>
            <div class="col-auto"><span class="badge bg-danger">Absent: {{ $sessionData->statusCounts['absent'] }}</span></div>
            <div class="col-auto"><span class="badge bg-warning text-dark">Late: {{ $sessionData->statusCounts['late'] }}</span></div>
            <div class="col-auto"><span class="badge bg-info">Excused: {{ $sessionData->statusCounts['excused'] }}</span></div>
            <div class="col-auto"><span class="badge bg-secondary">Not marked: {{ $sessionData->statusCounts['not_marked'] }}</span></div>
            <div class="col-auto ms-md-auto"><span class="badge bg-primary">Enrolled: {{ $sessionData->totalEnrolled }}</span></div>
        </div>

        @if($sessionData->rows->count())
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Trainee</th>
                        <th>CNIC</th>
                        <th>Status</th>
                        <th>Check-in</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessionData->rows as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ $row['enrollment']->trainee->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $row['enrollment']->trainee->email ?? '' }}</small>
                        </td>
                        <td>{{ $row['enrollment']->trainee->traineeProfile->cnic_no ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $statusBadges[$row['status']] ?? 'secondary' }}">
                                {{ $statusLabels[$row['status']] ?? ucfirst($row['status']) }}
                            </span>
                        </td>
                        <td>{{ $row['record']?->check_in_time?->format('h:i A') ?? '—' }}</td>
                        <td>{{ $row['record']?->remarks ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-muted mb-0">No trainees enrolled in this batch.</p>
        @endif
    </div>
</div>
