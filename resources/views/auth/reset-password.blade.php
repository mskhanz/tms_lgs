@extends('layouts.auth')

@section('title', 'Reset Password')

@section('header-title', 'Reset Password')

@section('header-subtitle', 'Choose a new password for your account')

@section('content')
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email', $request->email) }}"
                       required
                       autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       placeholder="Enter new password"
                       required>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password"
                       class="form-control"
                       id="password_confirmation"
                       name="password_confirmation"
                       placeholder="Confirm new password"
                       required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-shield-check me-2"></i>
            Reset Password
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="auth-link text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Back to Sign In
        </a>
    </div>
@endsection
