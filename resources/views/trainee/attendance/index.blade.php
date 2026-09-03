@extends('layouts.admin')

@section('title', 'My Attendance')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-calendar-check me-2"></i>My Attendance</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('trainee.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Attendance</li>
        </ol>
    </nav>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Overall attendance</div>
                <h2 class="mb-0 text-success">{{ number_format($overallPercentage, 1) }}%</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Sessions attended</div>
                <h2 class="mb-0 text-primary">{{ $presentCount }} / {{ $totalSessions }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Trainings with attendance</div>
                <h2 class="mb-0 text-info">{{ $enrollments->count() }}</h2>
            </div>
        </div>
    </div>
</div>

@if($enrollments->count())
<div class="row g-4">
    @foreach($enrollments as $enrollment)
        @php
            $batch = $enrollment->trainingBatch;
            $program = $batch?->trainingProgram;
            $minRequired = $batch?->effectiveMinAttendancePercentage();
            $percentage = (float) $enrollment->attendance_percentage;
            $meetsRequirement = $minRequired === null || $percentage >= $minRequired;
        @endphp
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <h5 class="mb-1">{{ $program->title ?? 'Training' }}</h5>
                        <small class="text-muted">{{ $batch->batch_code ?? 'N/A' }}</small>
                    </div>
                    <span class="badge bg-{{ $meetsRequirement ? 'success' : 'warning text-dark' }}">
                        {{ number_format($percentage, 1) }}%
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-2 small mb-3">
                        <div class="col-6">
                            <span class="text-muted">Batch dates</span>
                            <div class="fw-semibold">
                                {{ $batch->start_date?->format('d M Y') ?? 'N/A' }}
                                –
                                {{ $batch->end_date?->format('d M Y') ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Enrollment status</span>
                            <div class="fw-semibold">{{ ucwords(str_replace('_', ' ', $enrollment->status)) }}</div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Sessions marked</span>
                            <div class="fw-semibold">{{ $enrollment->attendance_records_count }}</div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Required minimum</span>
                            <div class="fw-semibold">{{ $minRequired !== null ? $minRequired.'%' : 'Not set' }}</div>
                        </div>
                    </div>

                    @if($minRequired !== null)
                    <div class="alert alert-{{ $meetsRequirement ? 'success' : 'warning' }} py-2 small mb-3">
                        @if($meetsRequirement)
                            You meet the minimum attendance requirement ({{ $minRequired }}%).
                        @else
                            Minimum attendance required is {{ $minRequired }}%. You are currently at {{ number_format($percentage, 1) }}%.
                        @endif
                    </div>
                    @endif

                    <a href="{{ route('trainee.attendance.show', $enrollment) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-list-check me-1"></i>View session details
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@else
<div class="alert alert-info mb-0">
    <i class="bi bi-info-circle me-2"></i>
    No attendance records are available yet. Attendance appears here once you are enrolled in a training batch with attendance tracking enabled.
</div>
@endif
@endsection
