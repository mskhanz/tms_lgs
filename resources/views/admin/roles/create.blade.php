@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Create Role</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles & Permissions</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="POST" action="{{ route('admin.roles.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-shield me-2"></i>Role Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Role Name (Identifier) <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}"
                               placeholder="e.g., training_coordinator"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Use lowercase letters and underscores only (e.g., training_coordinator)</div>
                    </div>

                    <div class="mb-3">
                        <label for="display_name" class="form-label fw-semibold">
                            Display Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="display_name" 
                               id="display_name" 
                               class="form-control @error('display_name') is-invalid @enderror" 
                               value="{{ old('display_name') }}"
                               placeholder="e.g., Training Coordinator"
                               required>
                        @error('display_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">User-friendly name shown in the interface</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Describe the purpose and responsibilities of this role...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-key me-2"></i>Assign Permissions</h5>
                </div>
                <div class="card-body">
                    @foreach($permissions as $group => $groupPermissions)
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2">
                            <i class="bi bi-folder me-2"></i>{{ ucwords(str_replace('_', ' ', $group)) }}
                        </h6>
                        <div class="row">
                            @foreach($groupPermissions as $permission)
                            <div class="col-md-6 col-lg-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           id="perm{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm{{ $permission->id }}">
                                        {{ $permission->display_name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Create Role
                </button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Role Creation Guide</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary"><i class="bi bi-lightbulb me-1"></i>Best Practices:</h6>
                    <ul class="small mb-3">
                        <li>Use descriptive role names</li>
                        <li>Assign only necessary permissions</li>
                        <li>Follow principle of least privilege</li>
                        <li>Document role purpose clearly</li>
                    </ul>

                    <h6 class="text-success"><i class="bi bi-shield-check me-1"></i>Permission Groups:</h6>
                    <div class="small">
                        <div class="mb-2"><strong>User Management:</strong> Create, edit, delete users</div>
                        <div class="mb-2"><strong>Training:</strong> Manage programs and batches</div>
                        <div class="mb-2"><strong>Enrollment:</strong> Handle trainee enrollments</div>
                        <div><strong>Reports:</strong> View and generate reports</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('styles')
<style>
    .form-label.fw-semibold {
        color: #047857;
    }
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #34d399 100%);
    }
    .card-header.bg-info {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%) !important;
    }
</style>
@endpush
@endsection
