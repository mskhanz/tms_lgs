@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('header-title', 'Forgot Password')

@section('header-subtitle', 'Reset your account password')

@section('content')
    <p class="text-muted mb-4">
        Enter your registered email address and we will send you a password reset link.
    </p>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       placeholder="Enter your registered email"
                       value="{{ old('email') }}"
                       required
                       autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-send me-2"></i>
            Send Reset Link
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="auth-link text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Back to Sign In
        </a>
    </div>
@endsection
