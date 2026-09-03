@extends('layouts.auth')

@section('title', 'Verify Email')

@section('header-title', 'Verify Your Email')

@section('header-subtitle', 'One more step to complete registration')

@section('content')
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="alert alert-info d-flex align-items-start" role="alert">
        <i class="bi bi-envelope-check me-2 mt-1 flex-shrink-0"></i>
        <span>
            A confirmation email with your login details has been sent to
            <strong>{{ auth()->user()->email }}</strong>.
            Please verify your email address using the link in that email before signing in.
        </span>
    </div>

    @if(session('status') === 'verification-link-sent')
    <div class="alert alert-success" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        A new verification link has been sent to your email address.
    </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-send me-2"></i>
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100 py-2">
            <i class="bi bi-box-arrow-right me-2"></i>
            Sign Out
        </button>
    </form>
@endsection
