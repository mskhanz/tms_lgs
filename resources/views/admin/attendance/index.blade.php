@extends('layouts.admin')

@section('title', 'Attendance Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-calendar-check me-2"></i>Attendance Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Search program</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Program title or code...">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Session report date</label>
                <input type="date" name="report_date" class="form-control" value="{{ $reportDate }}">
            </div>
            <input type="hidden" name="tab" value="{{ request('tab', 'manage') }}">
            <div class="col-md-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Apply</button>
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">Reset</a>
                <a href="{{ route('admin.attendance.session-report', ['date' => $reportDate]) }}" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-1"></i>Print report
                </a>
            </div>
        </form>
    </div>
</div>

<ul class="nav nav-tabs mb-4" id="attendanceTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab', 'manage') === 'manage' ? 'active' : '' }}"
                id="manage-tab"
                data-bs-toggle="tab"
                data-bs-target="#manage-panel"
                type="button"
                role="tab">
            <i class="bi bi-sliders me-1"></i>Batch Management
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') === 'sessions' ? 'active' : '' }}"
                id="sessions-tab"
                data-bs-toggle="tab"
                data-bs-target="#sessions-panel"
                type="button"
                role="tab">
            <i class="bi bi-calendar-event me-1"></i>Sessions ({{ $reportDate }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') === 'report' ? 'active' : '' }}"
                id="report-tab"
                data-bs-toggle="tab"
                data-bs-target="#report-panel"
                type="button"
                role="tab">
            <i class="bi bi-clipboard-data me-1"></i>Session Report ({{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }})
        </button>
    </li>
</ul>

<div class="tab-content" id="attendanceTabContent">
    <div class="tab-pane fade {{ request('tab', 'manage') === 'manage' ? 'show active' : '' }}" id="manage-panel" role="tabpanel">
@forelse($programs as $program)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-0">{{ $program->title }}</h5>
            <small class="text-muted">{{ $program->code }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-{{ $program->isAttendanceEnabled() ? 'success' : 'secondary' }}">
                Program: {{ $program->isAttendanceEnabled() ? 'Active' : 'Inactive' }}
            </span>
            <form method="POST" action="{{ route('admin.programs.attendance.toggle', $program) }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-{{ $program->isAttendanceEnabled() ? 'outline-danger' : 'outline-success' }}">
                    {{ $program->isAttendanceEnabled() ? 'Disable' : 'Enable' }}
                </button>
            </form>
            @if($program->isAttendanceEnabled() && $program->batches->count())
            <form method="POST" action="{{ route('admin.programs.attendance.activate-batches', $program) }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-primary">
                    Activate all batches
                </button>
            </form>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if($program->batches->count())
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Dates</th>
                        <th>Enrollments</th>
                        <th>Sessions</th>
                        <th>Attendance</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($program->batches as $batch)
                    <tr>
                        <td class="fw-semibold">
                            <a href="{{ route('admin.batches.show', $batch) }}">{{ $batch->batch_code }}</a>
                        </td>
                        <td>{{ $batch->start_date?->format('d M Y') }} – {{ $batch->end_date?->format('d M Y') }}</td>
                        <td>{{ $batch->enrollments_count }}</td>
                        <td>{{ $batch->sessions_count }}</td>
                        <td>
                            @if($batch->isAttendanceActive())
                                <span class="badge bg-success">Active</span>
                            @elseif($batch->attendance_enabled && $program->isAttendanceEnabled())
                                <span class="badge bg-warning text-dark">Enabled</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <form method="POST" action="{{ route('admin.batches.attendance.toggle', $batch) }}">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-{{ $batch->attendance_enabled ? 'danger' : 'success' }}"
                                            {{ ! $program->isAttendanceEnabled() ? 'disabled' : '' }}>
                                        {{ $batch->attendance_enabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                @if($batch->isAttendanceActive())
                                <a href="{{ route('admin.batches.attendance.show', $batch) }}" class="btn btn-sm btn-primary">
                                    Manage
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-muted text-center py-4 mb-0">No batches for this program.</p>
        @endif
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center text-muted py-5">
        No training programs found.
    </div>
</div>
@endforelse
    </div>

    <div class="tab-pane fade {{ request('tab') === 'sessions' ? 'show active' : '' }}" id="sessions-panel" role="tabpanel">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Sessions on {{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }}</h5>
            </div>
            <div class="card-body p-0">
                @if($sessionsForDate->count())
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Program</th>
                                <th>Batch</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Marked</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessionsForDate as $sessionData)
                            <tr>
                                <td class="fw-semibold">{{ $sessionData->session->title }}</td>
                                <td>{{ $sessionData->program->title ?? 'N/A' }}</td>
                                <td>{{ $sessionData->batch->batch_code ?? 'N/A' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($sessionData->session->start_time)->format('h:i A') }}
                                    –
                                    {{ \Carbon\Carbon::parse($sessionData->session->end_time)->format('h:i A') }}
                                </td>
                                <td>{{ $sessionData->session->venue ?? $sessionData->batch->venue ?? 'N/A' }}</td>
                                <td>
                                    {{ $sessionData->totalEnrolled - $sessionData->statusCounts['not_marked'] }}
                                    / {{ $sessionData->totalEnrolled }}
                                </td>
                                <td class="text-end">
                                    @if($sessionData->batch?->isAttendanceActive())
                                    <a href="{{ route('admin.batches.attendance.sessions.mark', [$sessionData->batch, $sessionData->session]) }}" class="btn btn-sm btn-primary">
                                        Mark
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4 mb-0">No sessions scheduled for this date.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="tab-pane fade {{ request('tab') === 'report' ? 'show active' : '' }}" id="report-panel" role="tabpanel">
        @if($sessionsForDate->count())
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted mb-0">
                    Attendance report for <strong>{{ $sessionsForDate->count() }}</strong> session(s) on {{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }}.
                </p>
                <a href="{{ route('admin.attendance.session-report', ['date' => $reportDate]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-printer me-1"></i>Print full report
                </a>
            </div>
            @foreach($sessionsForDate as $sessionData)
                @include('admin.attendance.partials.session-report-card', ['sessionData' => $sessionData])
            @endforeach
        @else
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                No sessions found for {{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }}.
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('#attendanceTabs .nav-link').forEach(function (tab) {
    tab.addEventListener('shown.bs.tab', function (event) {
        const tabId = event.target.id.replace('-tab', '');
        const map = { manage: 'manage', sessions: 'sessions', report: 'report' };
        const value = map[tabId] || 'manage';
        const url = new URL(window.location.href);
        url.searchParams.set('tab', value);
        window.history.replaceState({}, '', url);
        const tabInput = document.querySelector('input[name="tab"]');
        if (tabInput) {
            tabInput.value = value;
        }
    });
});
</script>
@endpush
