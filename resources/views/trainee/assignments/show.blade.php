@extends('layouts.admin')

@section('title', $assignment->title)

@push('styles')
@include('assignments._workspace-styles')
@endpush

@section('content')
@php
    $canEdit = $assignment->isAvailable() && (! $submission?->isSubmitted() || $assignment->canTraineeEditSubmission());
    if ($submission?->isSubmitted()) {
        $statusLabel = $submission->statusLabel();
        $statusClass = $submission->isLate() ? 'bg-warning text-dark' : 'bg-success text-white';
    } elseif ($submission) {
        $statusLabel = 'Draft';
        $statusClass = 'bg-info text-white';
    } else {
        $statusLabel = $assignment->traineeStatusLabel();
        $statusClass = $assignment->traineeStatus() === 'open' ? 'bg-secondary text-white' : 'bg-warning text-dark';
    }
@endphp

<div class="asg-workspace">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1">{{ $assignment->title }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('trainee.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('trainee.assignments.index') }}">Assignments</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('trainee.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>All assignments
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="asg-stats">
        <div class="asg-stat">
            <b><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></b>
            <span>Status</span>
        </div>
        <div class="asg-stat">
            <b>{{ $submission?->submitted_at?->format('d M Y, h:i A') ?? '—' }}</b>
            <span>Submitted</span>
        </div>
        <div class="asg-stat">
            <b>{{ $assignment->attachments->count() }}</b>
            <span>Materials</span>
        </div>
        <div class="asg-stat">
            <b>{{ $submission?->marks !== null ? $submission->marks.' / '.$assignment->total_marks : '—' }}</b>
            <span>Score</span>
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
                        <div class="asg-file-actions">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-asg-preview
                                    data-asg-name="{{ $file->displayName() }}"
                                    data-asg-kind="{{ $file->previewKind() }}"
                                    data-asg-view="{{ route('trainee.assignments.attachments.view', [$assignment, $file]) }}"
                                    data-asg-download="{{ route('trainee.assignments.attachments.download', [$assignment, $file]) }}">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <a href="{{ route('trainee.assignments.attachments.download', [$assignment, $file]) }}" class="btn btn-sm btn-outline-success">
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
            <h2>Your submission</h2>
            @if($submission?->submitted_at)
            <span class="badge bg-light text-dark">{{ $submission->submitted_at->format('d M Y, h:i A') }}</span>
            @endif
        </div>
        <div class="asg-panel-body">
            @if($submission?->marks !== null || $submission?->admin_feedback)
            <div class="asg-feedback">
                <div class="asg-feedback-title">Result from admin</div>
                @if($submission->marks !== null)
                <div class="fw-semibold mb-1">Score: {{ $submission->marks }} / {{ number_format((float) $assignment->total_marks, 0) }}</div>
                @endif
                @if($submission->admin_feedback)
                <div class="asg-prose">{{ $submission->admin_feedback }}</div>
                @endif
            </div>
            @endif

            @if($canEdit)
            <form method="POST" action="{{ route('trainee.assignments.submit', $assignment) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Written response</label>
                    <textarea name="written_response" id="written_response" class="form-control asg-richtext" rows="10"
                              placeholder="Write your answer or notes here...">{{ old('written_response', $submission->written_response ?? '') }}</textarea>
                    <div class="form-text">You can use bold, underline, colors, lists, and more.</div>
                </div>

                @if($submission && $submission->files->count())
                <div class="mb-3">
                    <label class="form-label fw-semibold">Uploaded files</label>
                    @foreach($submission->files as $file)
                    <div class="asg-file">
                        <div class="d-flex align-items-center gap-2" style="min-width:0;">
                            <div class="asg-file-icon"><i class="bi bi-file-earmark"></i></div>
                            <div style="min-width:0;">
                                <div class="asg-file-name">{{ $file->original_name }}</div>
                            </div>
                        </div>
                        <div class="asg-file-actions">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-asg-preview
                                    data-asg-name="{{ $file->original_name }}"
                                    data-asg-kind="{{ $file->previewKind() }}"
                                    data-asg-view="{{ route('trainee.assignments.files.view', [$assignment, $file]) }}"
                                    data-asg-download="{{ route('trainee.assignments.files.download', [$assignment, $file]) }}">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <a href="{{ route('trainee.assignments.files.download', [$assignment, $file]) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i>
                            </a>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="remove_files[]" value="{{ $file->id }}" id="rmf_{{ $file->id }}">
                                <label class="form-check-label text-danger small" for="rmf_{{ $file->id }}">Remove</label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload documents</label>
                    <input type="file" name="files[]" class="form-control" multiple
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                    <div class="form-text">Word, PDF, or images. Max 10 MB each.</div>
                    @error('files.*')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="asg-actions">
                    <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">
                        <i class="bi bi-save me-1"></i>Save draft
                    </button>
                    <button type="submit" name="action" value="submit" class="btn btn-success"
                            onclick="return confirm('Submit this assignment now?')">
                        <i class="bi bi-check-circle me-1"></i>Submit assignment
                    </button>
                </div>
            </form>
            @else
                @if($submission)
                <div class="asg-prose mb-3">{!! \App\Support\HtmlContent::display($submission->written_response, 'No written response.') !!}</div>
                @if($submission->files->count())
                <div class="mb-3">
                    @foreach($submission->files as $file)
                    <div class="asg-file">
                        <div class="d-flex align-items-center gap-2" style="min-width:0;">
                            <div class="asg-file-icon"><i class="bi bi-file-earmark"></i></div>
                            <div class="asg-file-name">{{ $file->original_name }}</div>
                        </div>
                        <div class="asg-file-actions">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-asg-preview
                                    data-asg-name="{{ $file->original_name }}"
                                    data-asg-kind="{{ $file->previewKind() }}"
                                    data-asg-view="{{ route('trainee.assignments.files.view', [$assignment, $file]) }}"
                                    data-asg-download="{{ route('trainee.assignments.files.download', [$assignment, $file]) }}">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <a href="{{ route('trainee.assignments.files.download', [$assignment, $file]) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                <p class="text-muted small mb-0">
                    @if($submission->submitted_at)
                        Submitted {{ $submission->submitted_at->format('d M Y, h:i A') }}.
                    @endif
                    @unless($assignment->canTraineeEditSubmission())
                        Editing is closed after the due date.
                    @endunless
                </p>
                @else
                <p class="asg-empty">This assignment is not open for submission.</p>
                @endif
            @endif
        </div>
    </div>
</div>

@include('assignments._file-preview-modal')
@include('assignments._richtext')
@include('assignments._due-countdown-script')
@endsection
