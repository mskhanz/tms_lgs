@extends('layouts.admin')

@section('title', $program->title)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-book me-2"></i>{{ $program->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">Programs</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-outline-warning">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('admin.attendance.index', ['search' => $program->code]) }}" class="btn btn-info">
                <i class="bi bi-calendar-check me-1"></i>Attendance
            </a>
            <a href="{{ route('admin.batches.create', ['training_program_id' => $program->id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>New Batch
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Status</div>
                <span class="badge bg-{{ $program->status === 'draft' ? 'warning' : ($program->status === 'approved' || $program->status === 'active' ? 'success' : 'secondary') }} fs-6">
                    {{ ucfirst(str_replace('_', ' ', $program->status)) }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="mb-0 text-primary">{{ $program->duration_days ?? '—' }}</h3>
                <small class="text-muted">Days ({{ $program->duration_hours ?? '—' }} hrs)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="mb-0 text-success">{{ $program->batches->count() }}</h3>
                <small class="text-muted">Batches</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Attendance</div>
                <span class="badge bg-{{ $program->isAttendanceEnabled() ? 'success' : 'secondary' }} fs-6">
                    {{ $program->isAttendanceEnabled() ? 'Enabled' : 'Disabled' }}
                </span>
                @if($program->min_attendance_percentage)
                    <div class="small text-muted mt-1">Min {{ $program->min_attendance_percentage }}%</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Program details</h5>
                <span class="text-muted small">{{ $program->code }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Category</div>
                        <div class="fw-semibold">{{ $program->categoryLabel() }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Type</div>
                        <div class="fw-semibold">{{ ucfirst($program->type) }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Conducting organization</div>
                        <div class="fw-semibold">{{ $program->conductingOrganization->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Budget allocated</div>
                        <div class="fw-semibold">{{ $program->budget_allocated !== null ? 'PKR '.number_format((float) $program->budget_allocated, 2) : 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Min / Max participants</div>
                        <div class="fw-semibold">{{ $program->min_participants ?? '—' }} / {{ $program->max_participants ?? '—' }}</div>
                    </div>
                </div>

                <h6 class="text-success">Description</h6>
                <p class="mb-4" style="white-space: pre-wrap;">{{ $program->description ?: 'N/A' }}</p>

                <h6 class="text-success">Objectives</h6>
                <p class="mb-4" style="white-space: pre-wrap;">{{ $program->objectives ?: 'N/A' }}</p>

                <h6 class="text-success">Target audience</h6>
                <p class="mb-0" style="white-space: pre-wrap;">{{ $program->target_audience ?: 'N/A' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Training batches</h5>
                <a href="{{ route('admin.batches.create', ['training_program_id' => $program->id]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add batch
                </a>
            </div>
            <div class="card-body p-0">
                @if($program->batches->count())
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Batch</th>
                                <th>Dates</th>
                                <th>Venue</th>
                                <th>Seats</th>
                                <th>Status</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($program->batches as $batch)
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('admin.batches.show', $batch) }}">{{ $batch->batch_code }}</a>
                                </td>
                                <td>
                                    {{ $batch->start_date?->format('d M Y') }}
                                    –
                                    {{ $batch->end_date?->format('d M Y') }}
                                </td>
                                <td>{{ $batch->venue ?? 'N/A' }}</td>
                                <td>{{ $batch->seats_filled }} / {{ $batch->total_seats }}</td>
                                <td>
                                    <span class="badge bg-{{ $batch->statusBadge() }}">{{ $batch->statusLabel() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $batch->isAttendanceActive() ? 'success' : 'secondary' }}">
                                        {{ $batch->isAttendanceActive() ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4 mb-0">No batches created for this program yet.</p>
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
                    <div class="text-muted small">Created by</div>
                    <div class="fw-semibold">{{ $program->createdBy->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Created at</div>
                    <div class="fw-semibold">{{ $program->created_at?->format('d M Y, h:i A') }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Last updated</div>
                    <div class="fw-semibold">{{ $program->updated_at?->format('d M Y, h:i A') }}</div>
                </div>
                @if($program->approvedBy)
                <div class="mb-3">
                    <div class="text-muted small">Approved by</div>
                    <div class="fw-semibold">{{ $program->approvedBy->name }}</div>
                </div>
                @endif
                @if($program->approved_at)
                <div>
                    <div class="text-muted small">Approved at</div>
                    <div class="fw-semibold">{{ $program->approved_at->format('d M Y, h:i A') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
