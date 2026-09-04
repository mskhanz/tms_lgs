@extends('layouts.admin')

@section('title', $assignment->title)

@push('styles')
@include('assignments._workspace-styles')
@endpush

@section('content')
@php
    $statusClass = $assignment->is_active ? 'bg-success text-white' : 'bg-secondary text-white';
    $statusLabel = $assignment->is_active ? 'Active' : 'Inactive';
@endphp

<div class="asg-workspace">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1">{{ $assignment->title }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Assignments</a></li>
                        <li class="breadcrumb-item active">Manage</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>All assignments
                </a>
                <a href="{{ route('admin.assignments.edit', $assignment) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form action="{{ route('admin.assignments.toggle-status', $assignment) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-{{ $assignment->is_active ? 'warning' : 'success' }}">
                        {{ $assignment->is_active ? 'Deactivate' : 'Activate & Notify' }}
                    </button>
                </form>
                <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this assignment?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Delete</button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="asg-stats">
        <div class="asg-stat">
            <b><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></b>
            <span>Status</span>
        </div>
        <div class="asg-stat">
            <b>{{ $assignedCount }}</b>
            <span>Assigned</span>
        </div>
        <div class="asg-stat">
            <b>{{ $submittedCount }}</b>
            <span>Submitted</span>
        </div>
        <div class="asg-stat">
            <b>{{ $assignment->attachments->count() }}</b>
            <span>Materials</span>
        </div>
    </div>

    <div class="row g-3 mb-3 align-items-start">
        <div class="col-lg-8">
            <div class="asg-panel asg-panel-auto">
                <div class="asg-panel-head"><h2>Assignment</h2></div>
                <div class="asg-panel-body">
                    <ul class="asg-meta-list asg-meta-list-left">
                        <li><span class="label">Title</span><span class="value">{{ $assignment->title }}</span></li>
                        <li><span class="label">Assigned to</span><span class="value">{{ $assignment->assignmentLabel() }}</span></li>
                        <li class="asg-meta-pair">
                            <div>
                                <span class="label">Total marks</span>
                                <span class="value">{{ number_format((float) $assignment->total_marks, 0) }}</span>
                            </div>
                            <div>
                                <span class="label">Due date</span>
                                @include('assignments._due-countdown')
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="asg-panel asg-panel-auto">
                <div class="asg-panel-head"><h2>Materials</h2></div>
                <div class="asg-panel-body">
                    @forelse($assignment->attachments as $file)
                    <div class="asg-file">
                        <div class="d-flex align-items-center gap-2" style="min-width:0;">
                            <div class="asg-file-icon"><i class="bi bi-paperclip"></i></div>
                            <div style="min-width:0;">
                                <div class="asg-file-name">{{ $file->displayName() }}</div>
                                @if($file->title && $file->title !== $file->original_name)
                                <div class="asg-file-sub">{{ $file->original_name }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="asg-file-actions asg-file-actions-stack">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    title="View"
                                    data-asg-preview
                                    data-asg-name="{{ $file->displayName() }}"
                                    data-asg-kind="{{ $file->previewKind() }}"
                                    data-asg-view="{{ route('admin.assignments.attachments.view', [$assignment, $file]) }}"
                                    data-asg-download="{{ route('admin.assignments.attachments.download', [$assignment, $file]) }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="{{ route('admin.assignments.attachments.download', [$assignment, $file]) }}" class="btn btn-sm btn-outline-success" title="Download">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="asg-empty">No materials attached.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="asg-panel asg-panel-auto mb-3">
        <div class="asg-panel-head"><h2>Instructions / Detail</h2></div>
        <div class="asg-panel-body">
            <div class="asg-prose">{!! \App\Support\HtmlContent::display($assignment->instructions, 'No instructions provided.') !!}</div>
        </div>
    </div>

    <div class="asg-panel asg-panel-auto">
        <div class="asg-panel-head">
            <h2>Submissions</h2>
            <span class="badge bg-light text-dark">{{ $assignment->submissions->count() }}</span>
        </div>
        <div class="asg-panel-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle asg-table">
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
                            $traineeName = $submission->user->traineeProfile->emp_name ?? $submission->user->name ?? 'Trainee';
                            $initials = strtoupper(substr($traineeName, 0, 1));
                            $contact = $submission->user->traineeProfile->contact_no ?? '—';
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
                                <strong>{{ $traineeName }}</strong>
                                <div class="small text-muted">{{ $contact }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $submission->isSubmitted() ? ($submission->isLate() ? 'warning text-dark' : 'success text-white') : 'secondary text-white' }}">
                                    {{ $submission->statusLabel() }}
                                </span>
                            </td>
                            <td>{{ $submission->submitted_at?->format('d M Y, h:i A') ?? '—' }}</td>
                            <td>{{ $submission->marks !== null ? $submission->marks.' / '.$assignment->total_marks : '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.assignments.submissions.show', [$assignment, $submission]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No submissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('assignments._file-preview-modal')
@include('assignments._due-countdown-script')
@endsection
