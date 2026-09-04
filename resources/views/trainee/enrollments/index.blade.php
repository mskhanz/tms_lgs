@extends('layouts.admin')

@section('title', 'My Enrollments')

@section('content')
@php
    $statusBadges = [
        'completed' => 'success',
        'in_progress' => 'primary',
        'enrolled' => 'warning text-dark',
        'dropped' => 'danger',
        'failed' => 'danger',
    ];
    $statusLabels = [
        'completed' => 'Completed',
        'in_progress' => 'Ongoing',
        'enrolled' => 'Enrolled',
        'dropped' => 'Dropped',
        'failed' => 'Failed',
    ];
    $pageTitle = match ($status) {
        'in_progress' => 'Ongoing Trainings',
        'completed' => 'Completed Trainings',
        'enrolled' => 'Enrolled Trainings',
        default => 'My Enrollments',
    };
@endphp

<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-journal-text me-2"></i>{{ $pageTitle }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('trainee.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Enrollments</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('trainee.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Dashboard
        </a>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('trainee.enrollments.index') }}"
       class="btn btn-sm {{ ! $status ? 'btn-success' : 'btn-outline-secondary' }}">
        All ({{ $counts['all'] }})
    </a>
    <a href="{{ route('trainee.enrollments.index', ['status' => 'in_progress']) }}"
       class="btn btn-sm {{ $status === 'in_progress' ? 'btn-primary' : 'btn-outline-primary' }}">
        Ongoing ({{ $counts['in_progress'] }})
    </a>
    <a href="{{ route('trainee.enrollments.index', ['status' => 'enrolled']) }}"
       class="btn btn-sm {{ $status === 'enrolled' ? 'btn-warning' : 'btn-outline-warning' }}">
        Enrolled ({{ $counts['enrolled'] }})
    </a>
    <a href="{{ route('trainee.enrollments.index', ['status' => 'completed']) }}"
       class="btn btn-sm {{ $status === 'completed' ? 'btn-success' : 'btn-outline-success' }}">
        Completed ({{ $counts['completed'] }})
    </a>
</div>

@if($enrollments->isEmpty())
<div class="alert alert-info mb-0">
    <i class="bi bi-info-circle me-2"></i>
    @if($status === 'in_progress')
        No ongoing trainings at the moment.
    @else
        No enrollments found for this filter.
    @endif
</div>
@else
<div class="row g-3">
    @foreach($enrollments as $enrollment)
    @php
        $batch = $enrollment->trainingBatch;
        $program = $batch?->trainingProgram;
    @endphp
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <h5 class="card-title h6 mb-0">{{ $program->title ?? 'Training' }}</h5>
                    <span class="badge bg-{{ $statusBadges[$enrollment->status] ?? 'secondary' }}">
                        {{ $statusLabels[$enrollment->status] ?? ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                    </span>
                </div>
                <ul class="list-unstyled small text-muted mb-3 flex-grow-1">
                    <li><i class="bi bi-hash me-1"></i>{{ $batch->batch_code ?? 'N/A' }}</li>
                    @if($batch?->start_date || $batch?->end_date)
                    <li>
                        <i class="bi bi-calendar-range me-1"></i>
                        {{ $batch->start_date?->format('d M Y') ?? '—' }}
                        –
                        {{ $batch->end_date?->format('d M Y') ?? '—' }}
                    </li>
                    @endif
                    @if($enrollment->enrollment_date)
                    <li><i class="bi bi-calendar-check me-1"></i>Enrolled: {{ $enrollment->enrollment_date->format('d M Y') }}</li>
                    @endif
                    @if($program?->conductingOrganization)
                    <li><i class="bi bi-building me-1"></i>{{ $program->conductingOrganization->name }}</li>
                    @endif
                    @if($enrollment->attendance_percentage !== null)
                    <li><i class="bi bi-person-check me-1"></i>Attendance: {{ number_format((float) $enrollment->attendance_percentage, 1) }}%</li>
                    @endif
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('trainee.attendance.show', $enrollment) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-calendar-check me-1"></i>Attendance
                    </a>
                    <a href="{{ route('trainee.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
