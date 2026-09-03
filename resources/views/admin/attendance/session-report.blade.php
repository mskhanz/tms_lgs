@extends('layouts.admin')

@section('title', 'Session Report - '.\Carbon\Carbon::parse($reportDate)->format('d M Y'))

@section('content')
<div class="page-header no-print">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-clipboard-data me-2"></i>Session Attendance Report</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
                    <li class="breadcrumb-item active">{{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button type="button" onclick="window.print()" class="btn btn-outline-primary">
                <i class="bi bi-printer me-1"></i>Print
            </button>
            <a href="{{ route('admin.attendance.index', ['report_date' => $reportDate, 'tab' => 'report']) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="card mb-4 no-print">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.attendance.session-report') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Report date</label>
                <input type="date" name="date" class="form-control" value="{{ $reportDate }}" required>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>View report</button>
            </div>
        </form>
    </div>
</div>

<div class="print-header d-none d-print-block mb-4">
    <h2 class="mb-1">Session Attendance Report</h2>
    <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($reportDate)->format('l, d M Y') }}</p>
</div>

@if($sessionsForDate->count())
    <div class="alert alert-info no-print">
        <i class="bi bi-info-circle me-2"></i>
        Showing <strong>{{ $sessionsForDate->count() }}</strong> session(s) scheduled on {{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }}.
    </div>

    @foreach($sessionsForDate as $sessionData)
        @include('admin.attendance.partials.session-report-card', ['sessionData' => $sessionData, 'showActions' => false])
    @endforeach
@else
<div class="card">
    <div class="card-body text-center text-muted py-5">
        No sessions found for {{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }}.
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .session-report-block { break-inside: avoid; page-break-inside: avoid; }
    }
</style>
@endpush
