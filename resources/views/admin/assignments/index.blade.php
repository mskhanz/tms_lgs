@extends('layouts.admin')

@section('title', 'Assignments')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2"></i>Assignments</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Assignments</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Create Assignment
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search assignments..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Assigned to</th>
                        <th>Marks</th>
                        <th>Files</th>
                        <th>Due</th>
                        <th>Submissions</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td>
                            <strong>{{ $assignment->title }}</strong>
                            @if($assignment->instructions)
                            <br><small class="text-muted">{{ Str::limit(strip_tags($assignment->instructions), 60) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($assignment->assign_to)
                                <span class="badge bg-{{ $assignment->assign_to === 'batch' ? 'primary' : 'info' }}">{{ $assignment->assign_to === 'batch' ? 'Batch' : 'Program' }}</span>
                                <div class="small text-muted">{{ $assignment->assignmentLabel() }}</div>
                            @else
                                <span class="badge bg-warning text-dark">Not assigned</span>
                            @endif
                        </td>
                        <td>{{ number_format((float) $assignment->total_marks, 0) }}</td>
                        <td><span class="badge bg-secondary">{{ $assignment->attachments_count }}</span></td>
                        <td>{{ $assignment->due_at?->format('d M Y') ?? '—' }}</td>
                        <td>{{ $assignment->submissions_count }}</td>
                        <td>
                            @if($assignment->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.assignments.show', $assignment) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.assignments.edit', $assignment) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No assignments yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($assignments->hasPages())
    <div class="card-footer">{{ $assignments->links() }}</div>
    @endif
</div>
@endsection
