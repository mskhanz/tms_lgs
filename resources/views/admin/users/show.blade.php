@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-person-circle me-2"></i>{{ $user->name }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
    </nav>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Edit User
        </a>
        @if(auth()->id() != $user->id)
        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }}">
                <i class="bi bi-{{ $user->is_active ? 'x-circle' : 'check-circle' }} me-2"></i>
                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
            <i class="bi bi-trash me-2"></i>Delete User
        </button>
        @endif
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Users
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- User Information Card -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>User Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                    @if($user->photo)
                        <img src="{{ asset('user_photos/' . $user->photo) }}" 
                             alt="{{ $user->name }}" 
                             style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-right: 20px;">
                    @else
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 2rem; margin-right: 20px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="mb-1">{{ $user->name }}</h3>
                        <p class="text-muted mb-1">{{ $user->email }}</p>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="badge bg-{{ 
                            $user->user_type == 'admin' ? 'danger' : 
                            ($user->user_type == 'trainer' ? 'warning' : 
                            ($user->user_type == 'staff' ? 'info' : 'secondary')) 
                        }}">
                            {{ ucfirst($user->user_type) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">User Type:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ ucfirst($user->user_type) }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Email:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $user->email }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Status:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                @if($user->phone)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Phone:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $user->phone }}
                    </div>
                </div>
                @endif

                @if($user->cnic)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">CNIC:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $user->cnic }}
                    </div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong class="text-muted">Total Roles:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-primary">{{ $user->roles->count() }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong class="text-muted">Joined:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $user->created_at->format('d M, Y h:i A') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Roles Card -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Assigned Roles ({{ $user->roles->count() }})</h5>
            </div>
            <div class="card-body">
                @if($user->roles->count() > 0)
                    <div class="row">
                        @foreach($user->roles as $role)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $role->display_name }}</h6>
                                        @if(in_array($role->name, ['system_admin', 'director', 'deputy_director']))
                                            <span class="badge bg-danger">System</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-2"><code>{{ $role->name }}</code></p>
                                    @if($role->description)
                                        <p class="small mb-2">{{ Str::limit($role->description, 80) }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-key me-1"></i>{{ $role->permissions->count() }} permissions
                                        </small>
                                        <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-outline-success">
                                            View Role
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-shield-x" style="font-size: 2rem;"></i>
                        <p class="mt-2">No roles assigned to this user</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Permissions Card -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-key me-2"></i>All Permissions (via Roles)</h5>
            </div>
            <div class="card-body">
                @php
                    $allPermissions = $user->roles->flatMap->permissions->unique('id');
                @endphp
                
                @if($allPermissions->count() > 0)
                    @php
                        $groupedPermissions = $allPermissions->groupBy('group');
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
                                <small>{{ $permission->display_name }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-key-fill" style="font-size: 2rem;"></i>
                        <p class="mt-2">No permissions available (no roles assigned)</p>
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
                        <i class="bi bi-shield-fill text-primary me-2"></i>
                        <strong>Roles</strong>
                    </div>
                    <h3 class="mb-0 text-primary">{{ $user->roles->count() }}</h3>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <div>
                        <i class="bi bi-key-fill text-success me-2"></i>
                        <strong>Permissions</strong>
                    </div>
                    <h3 class="mb-0 text-success">{{ $user->roles->flatMap->permissions->unique('id')->count() }}</h3>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <i class="bi bi-person-badge-fill text-info me-2"></i>
                        <strong>Status</strong>
                    </div>
                    <h5 class="mb-0">
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>

        @if(auth()->id() == $user->id)
        <!-- Current User Warning -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Current User</strong><br>
            <small>This is your account. You cannot delete or deactivate yourself.</small>
        </div>
        @endif

        <!-- Activity Timeline -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Activity</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-person-plus-fill text-success me-2 mt-1"></i>
                        <div>
                            <div class="fw-semibold">Account Created</div>
                            <small class="text-muted">{{ $user->created_at->format('d M, Y h:i A') }}</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-arrow-repeat text-info me-2 mt-1"></i>
                        <div>
                            <div class="fw-semibold">Last Updated</div>
                            <small class="text-muted">{{ $user->updated_at->format('d M, Y h:i A') }}</small>
                        </div>
                    </div>
                </div>
                @if($user->email_verified_at)
                <div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-envelope-check-fill text-primary me-2 mt-1"></i>
                        <div>
                            <div class="fw-semibold">Email Verified</div>
                            <small class="text-muted">{{ $user->email_verified_at->format('d M, Y h:i A') }}</small>
                        </div>
                    </div>
                </div>
                @else
                <div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-envelope-x-fill text-warning me-2 mt-1"></i>
                        <div>
                            <div class="fw-semibold">Email Not Verified</div>
                            <small class="text-muted">Pending verification</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Account Info -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Account Settings</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">User ID:</small><br>
                    <code>#{{ $user->id }}</code>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Email Status:</small><br>
                    <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                        {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                    </span>
                </div>
                <div>
                    <small class="text-muted">Account Status:</small><br>
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
@if(auth()->id() != $user->id)
<form id="delete-form" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endif

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
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
    .card.border-success {
        border-color: #10b981 !important;
    }
    .card.border-success:hover {
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
</style>
@endpush
@endsection
