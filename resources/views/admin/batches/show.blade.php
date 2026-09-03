@extends('layouts.admin')

@section('title', $batch->batch_code)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-collection me-2"></i>{{ $batch->batch_code }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            <a href="{{ route('admin.batches.edit', $batch) }}" class="btn btn-outline-warning">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            @if($batch->isAttendanceActive())
            <a href="{{ route('admin.batches.attendance.show', $batch) }}" class="btn btn-info">
                <i class="bi bi-calendar-check me-1"></i>Attendance
            </a>
            @endif
            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i>Enroll trainee
            </a>
        </div>
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
                <span class="badge bg-{{ $batch->statusBadge() }} fs-6">{{ $batch->statusLabel() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Attendance</div>
                <span class="badge bg-{{ $batch->isAttendanceActive() ? 'success' : 'secondary' }} fs-6">
                    {{ $batch->isAttendanceActive() ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="mb-0 text-primary">{{ $batch->total_seats }}</h3>
                <small class="text-muted">Total seats</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="mb-0 text-success">{{ $batch->seats_filled }}</h3>
                <small class="text-muted">Filled</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="mb-0 text-info">{{ $batch->seatsAvailable() }}</h3>
                <small class="text-muted">Available</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Min attendance</div>
                <div class="fw-semibold fs-5">{{ $batch->effectiveMinAttendancePercentage() ?? '—' }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Batch details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Program</div>
                        <div class="fw-semibold">
                            @if($batch->trainingProgram)
                                <a href="{{ route('admin.programs.show', $batch->trainingProgram) }}">
                                    {{ $batch->trainingProgram->title }}
                                </a>
                                <div class="text-muted small">{{ $batch->trainingProgram->code }}</div>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Coordinator</div>
                        <div class="fw-semibold">{{ $batch->coordinator->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Start date</div>
                        <div class="fw-semibold">{{ $batch->start_date?->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">End date</div>
                        <div class="fw-semibold">{{ $batch->end_date?->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Venue</div>
                        <div class="fw-semibold">{{ $batch->venue ?: 'N/A' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Venue address</div>
                        <div class="fw-semibold" style="white-space: pre-wrap;">{{ $batch->venue_address ?: 'N/A' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Remarks</div>
                        <div style="white-space: pre-wrap;">{{ $batch->remarks ?: 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Enrollments</h5>
            </div>
            <div class="card-body p-0">
                @if($batch->enrollments->count())
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Trainee</th>
                                <th>Enrollment date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->enrollments as $enrollment)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $enrollment->trainee->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $enrollment->trainee->email ?? '' }}</small>
                                </td>
                                <td>{{ $enrollment->enrollment_date?->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $enrollment->status)) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4 mb-0">No trainees enrolled in this batch yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Record info</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small">Created at</div>
                    <div class="fw-semibold">{{ $batch->created_at?->format('d M Y, h:i A') }}</div>
                </div>
                <div>
                    <div class="text-muted small">Last updated</div>
                    <div class="fw-semibold">{{ $batch->updated_at?->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
