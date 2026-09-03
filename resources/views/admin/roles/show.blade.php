@extends('layouts.admin')

@section('title', 'Role Details')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-shield-check me-2"></i>{{ $role->display_name }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles & Permissions</a></li>
            <li class="breadcrumb-item active">{{ $role->display_name }}</li>
        </ol>
    </nav>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Edit Role
        </a>
        @if(!in_array($role->name, ['system_admin', 'director', 'deputy_director']))
        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
            <i class="bi bi-trash me-2"></i>Delete Role
        </button>
        @endif
    </div>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Roles
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Role Information Card -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Role Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Identifier:</strong>
                    </div>
                    <div class="col-md-8">
                        <code>{{ $role->name }}</code>
                        @if(in_array($role->name, ['system_admin', 'director', 'deputy_director']))
                            <span class="badge bg-danger ms-2">System Role</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Display Name:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $role->display_name }}
                    </div>
                </div>

                @if($role->description)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Description:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $role->description }}
                    </div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Total Permissions:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-success">{{ $role->permissions->count() }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Users Assigned:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-primary">{{ $role->users->count() }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong class="text-muted">Created:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $role->created_at->format('d M, Y h:i A') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Card -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-key me-2"></i>Permissions ({{ $role->permissions->count() }})</h5>
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                    @php
                        $groupedPermissions = $role->permissions->groupBy('group');
                    @endphp
                    
                    @foreach($groupedPermissions as $group => $permissions)
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2">
                            <i class="bi bi-folder me-2"></i>{{ ucwords(str_replace('_', ' ', $group)) }}
                        </h6>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                {{ $permission->display_name }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-key-fill" style="font-size: 2rem;"></i>
                        <p class="mt-2">No permissions assigned to this role</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Users with This Role -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Users with This Role ({{ $role->users->count() }})</h5>
            </div>
            <div class="card-body">
                @if($role->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 35px; height: 35px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; margin-right: 10px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $user->user_type == 'admin' ? 'danger' : 
                                            ($user->user_type == 'trainer' ? 'warning' : 
                                            ($user->user_type == 'staff' ? 'info' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($user->user_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                        <p class="mt-2">No users assigned to this role</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Stats Card -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Quick Stats</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <div>
                        <i class="bi bi-key-fill text-success me-2"></i>
                        <strong>Permissions</strong>
                    </div>
                    <h3 class="mb-0 text-success">{{ $role->permissions->count() }}</h3>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        <strong>Users</strong>
                    </div>
                    <h3 class="mb-0 text-primary">{{ $role->users->count() }}</h3>
                </div>
            </div>
        </div>

        @if(in_array($role->name, ['system_admin', 'director', 'deputy_director']))
        <!-- System Role Warning -->
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>System Role</strong><br>
            <small>This is a protected system role. Changes should be made carefully as they affect critical system functionality.</small>
        </div>
        @endif

        <!-- Activity Timeline -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Activity</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-plus-circle-fill text-success me-2 mt-1"></i>
                        <div>
                            <div class="fw-semibold">Role Created</div>
                            <small class="text-muted">{{ $role->created_at->format('d M, Y h:i A') }}</small>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-arrow-repeat text-info me-2 mt-1"></i>
                        <div>
                            <div class="fw-semibold">Last Updated</div>
                            <small class="text-muted">{{ $role->updated_at->format('d M, Y h:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this role? Users with this role will lose associated permissions.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush

@push('styles')
<style>
    .card-header.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    }
</style>
@endpush
@endsection
