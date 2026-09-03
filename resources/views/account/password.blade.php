@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-key me-2"></i>Change Password</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @if(auth()->user()->isTrainee())
                        <li class="breadcrumb-item"><a href="{{ route('trainee.profile.show') }}">Profile</a></li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Profile</a></li>
                    @endif
                    <li class="breadcrumb-item active">Password</li>
                </ol>
            </nav>
        </div>
        <a href="{{ auth()->user()->isTrainee() ? route('trainee.profile.show') : route('account.profile') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <p class="text-muted">Enter your current password, then choose a new one. Other signed-in devices will be logged out.</p>

                <form method="POST" action="{{ route('account.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current password <span class="text-danger">*</span></label>
                        <input type="password"
                               name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               required
                               autocomplete="current-password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New password <span class="text-danger">*</span></label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required
                               autocomplete="new-password">
                        <div class="form-text">At least 8 characters, and different from your current password.</div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm new password <span class="text-danger">*</span></label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required
                               autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-shield-check me-2"></i>Update password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
