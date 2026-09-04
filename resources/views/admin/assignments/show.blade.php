@extends('layouts.admin')

@section('title', $assignment->title)

@section('content')
<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2"></i>{{ $assignment->title }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Assignments</a></li>
            <li class="breadcrumb-item active">Manage</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-primary">{{ $assignedCount }}</h3><small class="text-muted">Assigned trainees</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-success">{{ $submittedCount }}</h3><small class="text-muted">Submitted</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-info">{{ number_format((float) $assignment->total_marks, 0) }}</h3><small class="text-muted">Total marks</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-warning">{{ $assignment->due_at?->format('d M') ?? '∞' }}</h3><small class="text-muted">Due date</small></div></div></div>
</div>

<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.assignments.edit', $assignment) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
    <form action="{{ route('admin.assignments.toggle-status', $assignment) }}" method="POST">@csrf
        <button class="btn btn-outline-{{ $assignment->is_active ? 'warning' : 'success' }}">
            {{ $assignment->is_active ? 'Deactivate' : 'Activate & Notify' }}
        </button>
    </form>
    <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Delete this assignment?')">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Delete</button>
    </form>
</div>

<div class="alert {{ $assignment->assign_to ? 'alert-info' : 'alert-warning' }}">
    <strong>Assigned to:</strong> {{ $assignment->assignmentLabel() }}
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-9">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Instructions / Detail</h5></div>
            <div class="card-body" style="white-space: pre-wrap;">{{ $assignment->instructions ?: 'No instructions.' }}</div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Materials</h5></div>
            <div class="card-body">
                @forelse($assignment->attachments as $file)
                <div class="border rounded p-2 mb-2">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="small" style="min-width: 0;">
                            <strong class="d-block text-break">{{ $file->displayName() }}</strong>
                            @if($file->title && $file->title !== $file->original_name)
                            <span class="text-muted text-break">{{ $file->original_name }}</span>
                            @endif
                        </div>
                        <a href="{{ route('admin.assignments.attachments.download', [$assignment, $file]) }}" class="btn btn-sm btn-outline-primary flex-shrink-0">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">No files attached.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Submissions</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 56px;">S. No</th>
                                <th style="width: 64px;">Photo</th>
                                <th>Trainee</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Marks</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignment->submissions as $index => $submission)
                            @php
                                $photoUrl = $submission->user?->photoUrl();
                                $initials = strtoupper(substr($submission->user->traineeProfile->emp_name ?? $submission->user->name ?? 'T', 0, 1));
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="Photo" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                    @else
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-muted fw-semibold" style="width:40px;height:40px;">{{ $initials }}</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $submission->user->traineeProfile->emp_name ?? $submission->user->name }}</strong>
                                    <div class="small text-muted">{{ $submission->user->traineeProfile->cnic_no ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $submission->isSubmitted() ? ($submission->isLate() ? 'warning text-dark' : 'success text-white') : 'secondary text-white' }}">
                                        {{ $submission->statusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $submission->submitted_at?->format('d M Y H:i') ?? '—' }}</td>
                                <td>{{ $submission->marks !== null ? $submission->marks : '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.assignments.submissions.show', [$assignment, $submission]) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">No submissions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
