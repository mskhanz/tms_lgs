@extends('layouts.admin')

@section('title', 'Batch Attendance - '.$batch->batch_code)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-calendar-check me-2"></i>{{ $batch->batch_code }} Attendance</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
                    <li class="breadcrumb-item active">{{ $batch->batch_code }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">
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
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Program</div>
                <div class="fw-semibold">{{ $batch->trainingProgram->title ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Enrolled trainees</div>
                <div class="fw-semibold fs-4">{{ $batch->enrollments->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Minimum attendance</div>
                <div class="fw-semibold fs-4">{{ $batch->effectiveMinAttendancePercentage() ?? '—' }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add session</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.batches.attendance.sessions.store', $batch) }}" id="addSessionForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Session <span class="text-danger">*</span></label>
                        <select name="training_session_type_id"
                                id="training_session_type_id"
                                class="form-select @error('training_session_type_id') is-invalid @enderror"
                                required>
                            <option value="">Select session</option>
                            @foreach($sessionTypes as $sessionType)
                                <option value="{{ $sessionType->id }}"
                                        data-name="{{ $sessionType->name }}"
                                        {{ (string) old('training_session_type_id') === (string) $sessionType->id ? 'selected' : '' }}>
                                    {{ $sessionType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('training_session_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Session date</label>
                        <input type="date"
                               name="session_date"
                               id="session_date"
                               class="form-control @error('session_date') is-invalid @enderror"
                               value="{{ old('session_date', now()->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}"
                               required>
                        @error('session_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Sessions cannot be added for previous dates.</div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Start time</label>
                            <input type="time" name="start_time" class="form-control" value="{{ old('start_time', '09:00') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End time</label>
                            <input type="time" name="end_time" class="form-control" value="{{ old('end_time', '17:00') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control" value="{{ old('venue', $batch->venue) }}" placeholder="Optional">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Create session
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Training sessions</h5>
            </div>
            <div class="card-body p-0">
                @if($batch->sessions->count())
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Marked</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->sessions as $session)
                            @php $canMarkSession = \App\Services\AttendanceService::canMarkAttendanceForSession($session); @endphp
                            <tr>
                                <td class="fw-semibold">{{ $session->sessionType->name ?? $session->title }}</td>
                                <td>
                                    {{ $session->session_date?->format('d M Y') }}
                                    @if($session->session_date?->isPast() && ! $session->session_date?->isToday())
                                        <span class="badge bg-secondary ms-1">Locked</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}</td>
                                <td>{{ $session->attendance_records_count }} / {{ $batch->enrollments->count() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.batches.attendance.sessions.mark', [$batch, $session]) }}" class="btn btn-sm btn-{{ $canMarkSession ? 'primary' : 'outline-secondary' }}">
                                        {{ $canMarkSession ? 'Mark attendance' : 'View attendance' }}
                                    </a>
                                    <a href="{{ route('admin.attendance.index', ['report_date' => $session->session_date?->format('Y-m-d'), 'tab' => 'report']) }}" class="btn btn-sm btn-outline-secondary">
                                        View report
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4 mb-0">No sessions yet. Create a session to start marking attendance.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const usedSessionKeys = @json($usedSessionKeys);
    const dateInput = document.getElementById('session_date');
    const typeSelect = document.getElementById('training_session_type_id');

    function refreshSessionOptions() {
        if (!dateInput || !typeSelect) return;
        const date = dateInput.value;
        Array.from(typeSelect.options).forEach(function (option) {
            if (!option.value) return;
            const key = date + '|' + option.value;
            const used = usedSessionKeys.includes(key);
            option.hidden = used;
            option.disabled = used;
        });
        if (typeSelect.selectedOptions[0]?.disabled) {
            typeSelect.value = '';
        }
    }

    dateInput?.addEventListener('change', refreshSessionOptions);
    refreshSessionOptions();
})();
</script>
@endpush
