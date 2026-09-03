@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-person-lines-fill me-2"></i>Users</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Users</li>
        </ol>
    </nav>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New User
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
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">User Type</label>
                <select name="user_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="trainee" {{ request('user_type') == 'trainee' ? 'selected' : '' }}>Trainee</option>
                    <option value="trainer" {{ request('user_type') == 'trainer' ? 'selected' : '' }}>Trainer</option>
                    <option value="staff" {{ request('user_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->photo)
                                    <img src="{{ asset('user_photos/' . $user->photo) }}" 
                                         alt="{{ $user->name }}" 
                                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                                @else
                                    <div style="width: 40px; height: 40px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; margin-right: 12px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
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
                            @forelse($user->roles as $role)
                                <span class="badge bg-primary me-1">{{ $role->display_name }}</span>
                            @empty
                                <span class="text-muted small">No roles</span>
                            @endforelse
                        </td>
                        <td>
                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-sm btn-{{ $user->is_active ? 'success' : 'secondary' }}"
                                        onclick="return confirm('Toggle user status?')">
                                    <i class="bi bi-{{ $user->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>{{ $user->created_at->format('d M, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                   class="btn btn-outline-primary" 
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="btn btn-outline-warning" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id != auth()->id())
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        onclick="confirmDelete({{ $user->id }})"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </div>

                            <form id="delete-form-{{ $user->id }}" 
                                  action="{{ route('admin.users.destroy', $user->id) }}" 
                                  method="POST" 
                                  class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('admin.partials.pagination', ['paginator' => $users, 'label' => 'users'])
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        document.getElementById('delete-form-' + userId).submit();
    }
}
</script>
@endpush
@endsection
