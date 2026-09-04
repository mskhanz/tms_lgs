@extends('layouts.admin')

@section('title', 'Submission - '.$assignment->title)

@push('styles')
@include('assignments._workspace-styles')
@endpush

@section('content')
@php
    $trainee = $submission->user;
    $profile = $trainee->traineeProfile;
    $traineeName = $profile->emp_name ?? $trainee->name;
    $photoUrl = $trainee->photoUrl();
    $initials = strtoupper(substr($traineeName ?? 'T', 0, 1));
    $statusClass = $submission->isSubmitted()
        ? ($submission->isLate() ? 'bg-warning text-dark' : 'bg-success text-white')
        : 'bg-secondary text-white';
@endphp

<div class="asg-workspace">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1">Submission review</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Assignments</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.assignments.show', $assignment) }}">{{ Str::limit($assignment->title, 32) }}</a></li>
                        <li class="breadcrumb-item active">Submission</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.assignments.show', $assignment) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to assignment
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="asg-stats">
        <div class="asg-stat">
            <b><span class="badge {{ $statusClass }}">{{ $submission->statusLabel() }}</span></b>
            <span>Status</span>
        </div>
        <div class="asg-stat">
            <b>{{ $submission->submitted_at?->format('d M Y, h:i A') ?? '—' }}</b>
            <span>Submitted</span>
        </div>
        <div class="asg-stat">
            <b>{{ $submission->files->count() }}</b>
            <span>Files</span>
        </div>
        <div class="asg-stat">
            <b>{{ $submission->marks !== null ? $submission->marks.' / '.$assignment->total_marks : '—' }}</b>
            <span>Score</span>
        </div>
    </div>

    <div class="row g-3 mb-3 align-items-start">
        <div class="col-lg-4">
            <div class="asg-panel asg-panel-auto">
                <div class="asg-panel-head"><h2>Trainee</h2></div>
                <div class="asg-panel-body">
                    <div class="asg-trainee-row">
                        <div class="asg-trainee-photo">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $traineeName }}" class="asg-avatar">
                            @else
                                <div class="asg-avatar asg-avatar-fallback">{{ $initials }}</div>
                            @endif
                        </div>
                        <ul class="asg-meta-list asg-meta-list-left">
                            <li><span class="label">Name</span><span class="value">{{ $traineeName }}</span></li>
                            <li><span class="label">Contact no</span><span class="value">{{ $profile->contact_no ?? '—' }}</span></li>
                            <li><span class="label">Email</span><span class="value">{{ $trainee->email }}</span></li>
                            <li><span class="label">Organization</span><span class="value">{{ $profile?->organization?->name ?? '—' }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
                                <span class="value">{{ $assignment->due_at?->format('d M Y, h:i A') ?? 'No due date' }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="asg-panel asg-panel-auto mb-3">
        <div class="asg-panel-head"><h2>Written response</h2></div>
        <div class="asg-panel-body">
            <div class="asg-prose">{{ $submission->written_response ?: 'No written response provided.' }}</div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="asg-panel">
                <div class="asg-panel-head"><h2>Uploaded documents</h2></div>
                <div class="asg-panel-body">
                    @forelse($submission->files as $file)
                    <div class="asg-file">
                        <div class="d-flex align-items-center gap-2" style="min-width:0;">
                            <div class="asg-file-icon"><i class="bi bi-file-earmark"></i></div>
                            <div style="min-width:0;">
                                <div class="asg-file-name">{{ $file->original_name }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.assignments.files.download', [$assignment, $file]) }}" class="btn btn-sm btn-outline-success flex-shrink-0">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @empty
                    <p class="asg-empty">No documents uploaded.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="asg-panel">
                <div class="asg-panel-head"><h2>Marks &amp; feedback</h2></div>
                <div class="asg-panel-body">
                    <form method="POST" action="{{ route('admin.assignments.submissions.feedback', [$assignment, $submission]) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Marks (out of {{ number_format((float) $assignment->total_marks, 0) }})</label>
                            <input type="number" step="0.01" name="marks" class="form-control"
                                   value="{{ old('marks', $submission->marks) }}"
                                   min="0" max="{{ (float) $assignment->total_marks }}"
                                   placeholder="Enter marks">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Feedback</label>
                            <textarea name="admin_feedback" class="form-control" rows="5"
                                      placeholder="Write feedback for the trainee...">{{ old('admin_feedback', $submission->admin_feedback) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Save feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
