@extends('layouts.admin')

@section('title', 'Attendance Details')

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
    $program = $batch->trainingProgram;
    $minRequired = $batch->effectiveMinAttendancePercentage();
    $percentage = (float) $enrollment->attendance_percentage;
@endphp

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-calendar-check me-2"></i>My Attendance Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('trainee.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainee.attendance.index') }}">Attendance</a></li>
                    <li class="breadcrumb-item active">{{ $batch->batch_code }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('trainee.attendance.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Training program</div>
                <div class="fw-semibold">{{ $program->title ?? 'N/A' }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Batch</div>
                <div class="fw-semibold">{{ $batch->batch_code }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Your attendance</div>
                <div class="fw-semibold fs-5 text-success">{{ number_format($percentage, 1) }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-2 mb-4">
    <div class="col-auto"><span class="badge bg-success">Present: {{ $statusCounts['present'] }}</span></div>
    <div class="col-auto"><span class="badge bg-danger">Absent: {{ $statusCounts['absent'] }}</span></div>
    <div class="col-auto"><span class="badge bg-warning text-dark">Late: {{ $statusCounts['late'] }}</span></div>
    <div class="col-auto"><span class="badge bg-info">Excused: {{ $statusCounts['excused'] }}</span></div>
    <div class="col-auto"><span class="badge bg-secondary">Not marked: {{ $statusCounts['not_marked'] }}</span></div>
    @if($minRequired !== null)
    <div class="col-auto ms-md-auto"><span class="badge bg-primary">Required: {{ $minRequired }}%</span></div>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Session-wise attendance</h5>
    </div>
    <div class="card-body p-0">
        @if($sessionRows->count())
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Session</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Check-in</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessionRows as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $row->session->title }}</td>
                        <td>{{ $row->session->session_date?->format('d M Y') }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($row->session->start_time)->format('h:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($row->session->end_time)->format('h:i A') }}
                        </td>
                        <td>{{ $row->session->venue ?? $batch->venue ?? 'N/A' }}</td>
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
        <p class="text-muted text-center py-4 mb-0">No sessions have been scheduled for this batch yet.</p>
        @endif
    </div>
</div>
@endsection
