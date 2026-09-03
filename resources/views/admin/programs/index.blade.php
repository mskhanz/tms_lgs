@extends('layouts.admin')

@section('title', 'Training Programs')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-book me-2"></i>Training Programs</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Programs</li>
        </ol>
    </nav>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.programs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Program
        </a>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Print
        </button>
        <button class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel me-2"></i>Export
        </button>
    </div>
</div>

<!-- Alerts -->
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

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.programs.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                    <option value="leadership" {{ request('category') == 'leadership' ? 'selected' : '' }}>Leadership</option>
                    <option value="management" {{ request('category') == 'management' ? 'selected' : '' }}>Management</option>
                    <option value="specialized" {{ request('category') == 'specialized' ? 'selected' : '' }}>Specialized</option>
                    <option value="soft_skills" {{ request('category') == 'soft_skills' ? 'selected' : '' }}>Soft Skills</option>
                    <option value="mid_career_training" {{ request('category') == 'mid_career_training' ? 'selected' : '' }}>Mid Career Training</option>
                    <option value="pre_service_training" {{ request('category') == 'pre_service_training' ? 'selected' : '' }}>Pre-service Training</option>
                    <option value="pre_promotion_training" {{ request('category') == 'pre_promotion_training' ? 'selected' : '' }}>Pre-Promotion Training</option>
                    <option value="others" {{ request('category') == 'others' ? 'selected' : '' }}>Others</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="orientation" {{ request('type') == 'orientation' ? 'selected' : '' }}>Orientation</option>
                    <option value="induction" {{ request('type') == 'induction' ? 'selected' : '' }}>Induction</option>
                    <option value="refresher" {{ request('type') == 'refresher' ? 'selected' : '' }}>Refresher</option>
                    <option value="specialized" {{ request('type') == 'specialized' ? 'selected' : '' }}>Specialized</option>
                    <option value="advanced" {{ request('type') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Title, code..." value="{{ request('search') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Programs Grid -->
<div class="row g-4">
    @forelse($programs as $program)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 program-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="badge bg-{{ $program->status == 'active' ? 'success' : ($program->status == 'draft' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($program->status) }}
                </span>
                <span class="text-muted small">{{ $program->code }}</span>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $program->title }}</h5>
                <p class="card-text text-muted small">{{ Str::limit($program->description, 100) }}</p>
                
                <div class="program-meta mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small"><i class="bi bi-tag me-1"></i>Category:</span>
                        <span class="badge bg-info">{{ $program->categoryLabel() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small"><i class="bi bi-bookmark me-1"></i>Type:</span>
                        <span class="badge bg-secondary">{{ ucfirst($program->type) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small"><i class="bi bi-clock me-1"></i>Duration:</span>
                        <span class="fw-semibold">{{ $program->duration_days }} days ({{ $program->duration_hours }}hrs)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-collection me-1"></i>Batches:</span>
                        <span class="fw-semibold text-primary">{{ $program->batches_count }}</span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="btn-group btn-group-sm w-100">
                    <a href="{{ route('admin.programs.show', $program->id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>View
                    </a>
                    <a href="{{ route('admin.programs.edit', $program->id) }}" class="btn btn-outline-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $program->id }})">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </div>
                
                <!-- Delete Form -->
                <form id="delete-form-{{ $program->id }}" 
                      action="{{ route('admin.programs.destroy', $program->id) }}" 
                      method="POST" 
                      class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
            <p class="text-muted mt-3 mb-0">No training programs found</p>
            <a href="{{ route('admin.programs.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle me-2"></i>Create First Program
            </a>
        </div>
    </div>
    @endforelse
</div>

@include('admin.partials.pagination', ['paginator' => $programs, 'label' => 'programs'])

@push('scripts')
<script>
function confirmDelete(programId) {
    if (confirm('Are you sure you want to delete this training program? This action cannot be undone.')) {
        document.getElementById('delete-form-' + programId).submit();
    }
}
</script>
@endpush

@push('styles')
<style>
    .program-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: #10b981;
    }
    .program-card .card-title {
        color: #047857;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .program-meta {
        border-top: 1px solid #f3f4f6;
        padding-top: 12px;
    }
    @media print {
        .page-header, .btn, .pagination, .card-body form {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
