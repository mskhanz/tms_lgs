@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-activity me-2"></i>Activity detail</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.activity-logs.index') }}">Activity Logs</a></li>
                    <li class="breadcrumb-item active">#{{ $log->id }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">{{ $log->description }}</h5>
                <dl class="row mb-0">
                    <dt class="col-sm-3">Time</dt>
                    <dd class="col-sm-9">{{ $log->created_at->format('d M Y, h:i:s A') }}</dd>
                    <dt class="col-sm-3">Module</dt>
                    <dd class="col-sm-9">{{ $log->log_name ? ucfirst($log->log_name) : '—' }}</dd>
                    <dt class="col-sm-3">User</dt>
                    <dd class="col-sm-9">
                        @if($log->user)
                            {{ $log->user->name }} <span class="text-muted">({{ $log->user->email }})</span>
                        @else
                            System / guest
                        @endif
                    </dd>
                    <dt class="col-sm-3">IP address</dt>
                    <dd class="col-sm-9"><code>{{ $log->ip_address ?? '—' }}</code></dd>
                    <dt class="col-sm-3">User agent</dt>
                    <dd class="col-sm-9"><small>{{ $log->user_agent ?? '—' }}</small></dd>
                    <dt class="col-sm-3">Subject</dt>
                    <dd class="col-sm-9">
                        @if($log->subject_type)
                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                        @else
                            —
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <strong>Properties</strong>
            </div>
            <div class="card-body">
                @if(!empty($log->properties))
                    <pre class="mb-0 small bg-light p-3 rounded">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                @else
                    <p class="text-muted mb-0">No extra properties.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
