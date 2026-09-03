@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit User</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Edit</li>
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

<form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label fw-semibold">
                            Profile Photo
                        </label>
                        @if($user->photo)
                        <div class="mb-2">
                            <img src="{{ asset('user_photos/' . $user->photo) }}" 
                                 alt="Current photo" 
                                 class="rounded-circle"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        @endif
                        <input type="file" 
                               name="photo" 
                               id="photo" 
                               class="form-control @error('photo') is-invalid @enderror" 
                               accept="image/*">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum file size: 2MB. Leave empty to keep current photo</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Leave password fields empty to keep current password
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">
                                New Password
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 8 characters</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                Confirm New Password
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="user_type" class="form-label fw-semibold">
                            User Type <span class="text-danger">*</span>
                        </label>
                        <select name="user_type" 
                                id="user_type" 
                                class="form-select @error('user_type') is-invalid @enderror"
                                required>
                            <option value="trainee" {{ old('user_type', $user->user_type) == 'trainee' ? 'selected' : '' }}>Trainee</option>
                            <option value="trainer" {{ old('user_type', $user->user_type) == 'trainer' ? 'selected' : '' }}>Trainer</option>
                            <option value="staff" {{ old('user_type', $user->user_type) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="admin" {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('user_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role_id" class="form-label fw-semibold">
                            Assign Role
                        </label>
                        <select name="role_id" 
                                id="role_id" 
                                class="form-select @error('role_id') is-invalid @enderror">
                            <option value="">-- Select Role (Optional) --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" 
                                {{ old('role_id', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                                @if($role->description)
                                    - {{ $role->description }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Select a role to grant additional permissions</div>
                    </div>

                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="is_active" 
                               value="1"
                               id="is_active"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            Account Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>User Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Created:</strong> {{ $user->created_at->format('d M, Y h:i A') }}
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong> {{ $user->updated_at->format('d M, Y h:i A') }}
                    </div>
                    <div>
                        <strong>Profile:</strong> 
                        <span class="badge bg-{{ $user->profile_completed ? 'success' : 'warning' }}">
                            {{ $user->profile_completed ? 'Completed' : 'Incomplete' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Important</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Changing user type may affect access permissions</li>
                        <li>Role assignments override default user type permissions</li>
                        <li>Inactive users cannot log in to the system</li>
                    </ul>
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
</style>
@endpush
@endsection
