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
    $compact = $compact ?? false;
@endphp

<div class="card mb-4 trainee-enrollments-card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="bi bi-journal-text me-2"></i>
            Enrollments ({{ $enrollmentSummaries->count() }})
        </h5>
        @if($attendanceOverview['totalSessions'] > 0)
        <span class="badge bg-success">
            Overall attendance: {{ number_format($attendanceOverview['overallPercentage'], 1) }}%
        </span>
        @endif
    </div>
    <div class="card-body">
        @forelse($enrollmentSummaries as $summary)
            @php
                $enrollment = $summary->enrollment;
                $batch = $summary->batch;
                $program = $summary->program;
                $minRequired = $batch?->effectiveMinAttendancePercentage();
            @endphp
            <div class="enrollment-block {{ ! $loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h6 class="mb-1 fw-semibold">{{ $program->title ?? 'Training not set' }}</h6>
                        <div class="small text-muted">
                            Batch <strong class="text-body">{{ $batch->batch_code ?? 'N/A' }}</strong>
                            @if($program?->code)
                                <span class="ms-1">({{ $program->code }})</span>
                            @endif
                        </div>
                    </div>
                    <span class="badge enrollment-status-badge
                        {{ $enrollment->status === 'completed' ? 'bg-success' : '' }}
                        {{ $enrollment->status === 'in_progress' ? 'bg-primary' : '' }}
                        {{ $enrollment->status === 'enrolled' ? 'bg-warning text-dark' : '' }}
                        {{ in_array($enrollment->status, ['dropped', 'failed']) ? 'bg-danger' : '' }}">
                        {{ ucwords(str_replace('_', ' ', $enrollment->status)) }}
                    </span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Enrolled on</div>
                        <div class="fw-semibold">{{ $enrollment->enrollment_date?->format('d M, Y') ?? 'N/A' }}</div>
                    </div>
                    @if($batch?->start_date && $batch?->end_date)
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Training dates</div>
                        <div class="fw-semibold">
                            {{ $batch->start_date->format('d M, Y') }} – {{ $batch->end_date->format('d M, Y') }}
                        </div>
                    </div>
                    @endif
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Batch status</div>
                        @if($batch)
                        <span class="badge bg-{{ $batch->statusBadge() }}">{{ $batch->statusLabel() }}</span>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Venue</div>
                        <div class="fw-semibold">{{ $batch->venue ?? 'N/A' }}</div>
                    </div>
                    @if($enrollment->enrolledBy)
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Enrolled by</div>
                        <div class="fw-semibold">{{ $enrollment->enrolledBy->name }}</div>
                    </div>
                    @endif
                    @if($enrollment->completion_date)
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Completion date</div>
                        <div class="fw-semibold">{{ $enrollment->completion_date->format('d M, Y') }}</div>
                    </div>
                    @endif
                    @if($enrollment->assessment_score !== null)
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Assessment score</div>
                        <div class="fw-semibold">{{ number_format((float) $enrollment->assessment_score, 1) }}%</div>
                    </div>
                    @endif
                </div>

                @if($summary->showAttendance)
                <div class="attendance-panel border rounded p-3 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <div class="fw-semibold"><i class="bi bi-calendar-check me-1"></i>Attendance</div>
                            <div class="small text-muted">
                                {{ number_format((float) $enrollment->attendance_percentage, 1) }}%
                                @if($minRequired !== null)
                                    · Required {{ $minRequired }}%
                                    @if((float) $enrollment->attendance_percentage >= $minRequired)
                                        <span class="badge bg-success ms-1">Met</span>
                                    @else
                                        <span class="badge bg-danger ms-1">Below required</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @if($batch && ! $compact)
                        <a href="{{ route('admin.batches.attendance.show', $batch->id) }}" class="btn btn-sm btn-outline-primary no-print">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Batch attendance
                        </a>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-success">Present: {{ $summary->statusCounts['present'] }}</span>
                        <span class="badge bg-danger">Absent: {{ $summary->statusCounts['absent'] }}</span>
                        <span class="badge bg-warning text-dark">Late: {{ $summary->statusCounts['late'] }}</span>
                        <span class="badge bg-info">Excused: {{ $summary->statusCounts['excused'] }}</span>
                        <span class="badge bg-secondary">Not marked: {{ $summary->statusCounts['not_marked'] }}</span>
                    </div>

                    @if($summary->sessionRows->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0 bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Session</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Check-in</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary->sessionRows as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $row->session->title }}
                                        @if($row->session->sessionType)
                                            <span class="text-muted small">({{ $row->session->sessionType->name }})</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->session->session_date?->format('d M Y') }}</td>
                                    <td>
                                        @if($row->session->start_time && $row->session->end_time)
                                            {{ \Carbon\Carbon::parse($row->session->start_time)->format('h:i A') }}
                                            –
                                            {{ \Carbon\Carbon::parse($row->session->end_time)->format('h:i A') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusBadges[$row->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$row->status] ?? ucfirst($row->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $row->record?->check_in_time?->format('h:i A') ?? '—' }}</td>
                                    <td>{{ $row->record?->remarks ?? '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted small mb-0">No sessions scheduled for this batch yet.</p>
                    @endif
                </div>
                @elseif($batch && $batch->isAttendanceEnabled())
                <div class="alert alert-light border small mb-0 py-2">
                    <i class="bi bi-info-circle me-1"></i>Attendance is enabled but no sessions have been marked yet.
                </div>
                @endif

                @if($enrollment->remarks)
                <div class="mt-3 small">
                    <span class="text-muted">Enrollment remarks:</span> {{ $enrollment->remarks }}
                </div>
                @endif
            </div>
        @empty
            <p class="text-muted text-center mb-0 py-3">No enrollments yet.</p>
        @endforelse
    </div>
</div>
