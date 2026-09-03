@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Create User</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<!-- Alerts -->
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

<form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
    @csrf
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
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label fw-semibold">
                            Profile Photo
                        </label>
                        <input type="file" 
                               name="photo" 
                               id="photo" 
                               class="form-control @error('photo') is-invalid @enderror" 
                               accept="image/*">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum file size: 2MB. Formats: JPG, PNG, GIF</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">
                                Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 8 characters</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                Confirm Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control"
                                   required>
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
                            <option value="">-- Select User Type --</option>
                            <option value="trainee" {{ old('user_type', request('user_type')) == 'trainee' ? 'selected' : '' }}>Trainee</option>
                            <option value="trainer" {{ old('user_type', request('user_type')) == 'trainer' ? 'selected' : '' }}>Trainer</option>
                            <option value="staff" {{ old('user_type', request('user_type')) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="admin" {{ old('user_type', request('user_type')) == 'admin' ? 'selected' : '' }}>Admin</option>
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
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            Account Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>User Creation Guide</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary"><i class="bi bi-lightbulb me-1"></i>Tips:</h6>
                    <ul class="small mb-3">
                        <li>Use official email addresses</li>
                        <li>Create strong passwords (min 8 characters)</li>
                        <li>Assign appropriate roles for permissions</li>
                        <li>User type determines default access level</li>
                    </ul>

                    <h6 class="text-success"><i class="bi bi-shield-check me-1"></i>User Types:</h6>
                    <div class="small">
                        <div class="mb-2">
                            <strong>Trainee:</strong> Training participants
                        </div>
                        <div class="mb-2">
                            <strong>Trainer:</strong> Training instructors
                        </div>
                        <div class="mb-2">
                            <strong>Staff:</strong> Administrative staff
                        </div>
                        <div>
                            <strong>Admin:</strong> System administrators
                        </div>
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
