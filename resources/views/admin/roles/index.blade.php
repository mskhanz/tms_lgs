@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-shield-check me-2"></i>Roles & Permissions</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Roles & Permissions</li>
        </ol>
    </nav>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Role
        </a>
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

<!-- Roles Grid -->
<div class="row g-4">
    @forelse($roles as $role)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 role-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h5 class="mb-0 text-primary">{{ $role->display_name }}</h5>
                @if(in_array($role->name, ['system_admin', 'director', 'deputy_director']))
                    <span class="badge bg-danger">System Role</span>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-muted small">Identifier:</strong>
                    <code class="ms-2">{{ $role->name }}</code>
                </div>
                
                @if($role->description)
                <p class="text-muted small mb-3">{{ $role->description }}</p>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small"><i class="bi bi-people me-1"></i>Users:</span>
                    <span class="fw-semibold text-primary">{{ $role->users_count }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small"><i class="bi bi-key me-1"></i>Permissions:</span>
                    <span class="fw-semibold text-success">{{ $role->permissions_count }}</span>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="btn-group w-100">
                    <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>View
                    </a>
                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    @if(!in_array($role->name, ['system_admin', 'director', 'deputy_director']))
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger" 
                            onclick="confirmDelete({{ $role->id }})">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                    @endif
                </div>

                <form id="delete-form-{{ $role->id }}" 
                      action="{{ route('admin.roles.destroy', $role->id) }}" 
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
            <i class="bi bi-shield-x" style="font-size: 4rem; color: #ccc;"></i>
            <p class="text-muted mt-3 mb-0">No roles found</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Permissions Reference -->
<div class="card mt-5">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Available Permissions</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($permissions as $group => $groupPermissions)
            <div class="col-md-6 col-lg-4 mb-4">
                <h6 class="text-primary mb-3">
                    <i class="bi bi-folder me-2"></i>{{ ucwords(str_replace('_', ' ', $group)) }}
                </h6>
                <ul class="list-unstyled ps-3">
                    @foreach($groupPermissions as $permission)
                    <li class="mb-2">
                        <i class="bi bi-key-fill text-success me-2"></i>
                        <span class="small">{{ $permission->display_name }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(roleId) {
    if (confirm('Are you sure you want to delete this role? Users with this role will lose associated permissions.')) {
        document.getElementById('delete-form-' + roleId).submit();
    }
}
</script>
@endpush

@push('styles')
<style>
    .role-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: #10b981;
    }
</style>
@endpush
@endsection
