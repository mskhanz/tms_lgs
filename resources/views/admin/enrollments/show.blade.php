@extends('layouts.admin')

@section('title', 'Enrollment Details')

@php
    $trainee = $enrollment->trainee;
    $profile = $trainee?->traineeProfile;
    $batch = $enrollment->trainingBatch;
    $program = $batch?->trainingProgram;
    $statusColors = [
        'enrolled' => 'primary',
        'in_progress' => 'info',
        'completed' => 'success',
        'dropped' => 'warning',
        'failed' => 'danger',
    ];
    $statusColor = $statusColors[$enrollment->status] ?? 'secondary';
@endphp

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-person-check me-2"></i>Enrollment Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.enrollments.index') }}">Enrollments</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Status</div>
                <span class="badge bg-{{ $statusColor }} fs-6">{{ ucwords(str_replace('_', ' ', $enrollment->status)) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="mb-0 text-primary">{{ $enrollment->enrollment_date?->format('d M Y') ?? '—' }}</h3>
                <small class="text-muted">Enrollment date</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="mb-0 text-success">{{ number_format((float) $enrollment->attendance_percentage, 1) }}%</h3>
                <small class="text-muted">Attendance</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="mb-0 text-info">{{ $enrollment->assessment_score !== null ? number_format((float) $enrollment->assessment_score, 1) : '—' }}</h3>
                <small class="text-muted">Assessment score</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Trainee</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($trainee && $trainee->photoUrl())
                        <img src="{{ $trainee->photoUrl() }}" alt="{{ $trainee->name }}" style="width: 64px; height: 64px; object-fit: cover; border-radius: 50%;">
                    @else
                        <div style="width: 64px; height: 64px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.5rem;">
                            {{ strtoupper(substr($trainee->name ?? 'T', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="fw-semibold fs-5">{{ $trainee->name ?? 'N/A' }}</div>
                        <div class="text-muted">{{ $trainee->email ?? '' }}</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">CNIC</div>
                        <div class="fw-semibold">{{ $profile->cnic_no ?? $profile->cnic ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Designation</div>
                        <div class="fw-semibold">{{ $profile->designation ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Organization</div>
                        <div class="fw-semibold">{{ $profile?->organization?->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">BPS</div>
                        <div class="fw-semibold">{{ $profile->bps ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Training</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Program</div>
                        <div class="fw-semibold">
                            @if($program)
                                <a href="{{ route('admin.programs.show', $program) }}">{{ $program->title }}</a>
                                <div class="text-muted small">{{ $program->code }}</div>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Batch</div>
                        <div class="fw-semibold">
                            @if($batch)
                                <a href="{{ route('admin.batches.show', $batch) }}">{{ $batch->batch_code }}</a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Dates</div>
                        <div class="fw-semibold">
                            {{ $batch?->start_date?->format('d M Y') ?? 'N/A' }}
                            –
                            {{ $batch?->end_date?->format('d M Y') ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Venue</div>
                        <div class="fw-semibold">{{ $batch?->venue ?: 'N/A' }}</div>
                    </div>
                    @if($batch && $batch->trainers->count())
                    <div class="col-12">
                        <div class="text-muted small">Trainers</div>
                        <div class="fw-semibold">
                            {{ $batch->trainers->pluck('name')->filter()->join(', ') ?: 'N/A' }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Attendance</h5>
            </div>
            <div class="card-body p-0">
                @if($enrollment->attendanceRecords->count())
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Status</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollment->attendanceRecords as $record)
                            <tr>
                                <td>{{ $record->trainingSession->title ?? $record->trainingSession->session_title ?? 'Session #'.$record->training_session_id }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($record->status) }}</span></td>
                                <td>{{ $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') : '—' }}</td>
                                <td>{{ $record->check_out_time ? \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') : '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4 mb-0">No attendance records yet.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assessments</h5>
            </div>
            <div class="card-body p-0">
                @if($enrollment->assessmentResults->count())
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Assessment</th>
                                <th>Marks</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollment->assessmentResults as $result)
                            <tr>
                                <td>{{ $result->assessment->title ?? 'Assessment #'.$result->assessment_id }}</td>
                                <td>{{ $result->obtained_marks }} ({{ $result->percentage }}%)</td>
                                <td>{{ ucfirst($result->result ?? $result->status ?? 'N/A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4 mb-0">No assessment results yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Update status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.enrollments.update-status', $enrollment->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="enrolled" {{ $enrollment->status == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                            <option value="in_progress" {{ $enrollment->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $enrollment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="dropped" {{ $enrollment->status == 'dropped' ? 'selected' : '' }}>Dropped</option>
                            <option value="failed" {{ $enrollment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Completion date</label>
                        <input type="date" name="completion_date" class="form-control" value="{{ $enrollment->completion_date?->format('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ $enrollment->remarks }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Record info</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small">Enrolled by</div>
                    <div class="fw-semibold">{{ $enrollment->enrolledBy->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Created at</div>
                    <div class="fw-semibold">{{ $enrollment->created_at?->format('d M Y, h:i A') }}</div>
                </div>
                <div>
                    <div class="text-muted small">Last updated</div>
                    <div class="fw-semibold">{{ $enrollment->updated_at?->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
