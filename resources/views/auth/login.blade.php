@extends('layouts.auth')

@section('title', 'Login')

@section('content')
@if($errors->any())
<div class="alert-auth error">
    <i class="bi bi-exclamation-circle-fill"></i>
    <span>{{ $errors->first() }}</span>
</div>
@endif

@if(session('success'))
<div class="alert-auth success">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('warning'))
<div class="alert-auth warning">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span>{{ session('warning') }}</span>
</div>
@endif

@if(session('status'))
<div class="alert-auth success">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('status') }}</span>
</div>
@endif

<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf

    <div class="form-floating-custom">
        <label for="email">Email Address</label>
        <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" id="email"
                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email') }}"
                   placeholder="your@email.com"
                   required autofocus autocomplete="email">
        </div>
        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>

    <div class="form-floating-custom">
        <label for="password">Password</label>
        <div class="input-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" id="password"
                   class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="••••••••"
                   required autocomplete="current-password">
            <button class="toggle-btn" type="button" id="togglePassword" tabindex="-1" aria-label="Toggle password visibility">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
        </div>
        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>

    <div class="remember-row">
        <div class="remember-check">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Keep me signed in</label>
        </div>
        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
    </div>

    <button type="submit" class="btn-signin" id="signinBtn">
        <span class="btn-signin-idle">
            <i class="bi bi-box-arrow-in-right"></i>
            Sign In
        </span>
        <span class="btn-signin-busy d-none">
            <span class="btn-signin-spinner" aria-hidden="true"></span>
            Signing in…
        </span>
    </button>
</form>

<div class="auth-register-link">
    Don't have an account? <a href="{{ route('register') }}">Register as Trainee</a>
</div>

<a href="{{ route('home') }}" class="auth-home-link">
    <i class="bi bi-arrow-left"></i> Back to home
</a>

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            pwd.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });

    (function () {
        const form = document.getElementById('loginForm');
        const btn = document.getElementById('signinBtn');
        if (!form || !btn) return;

        form.addEventListener('submit', function () {
            if (btn.classList.contains('is-loading')) {
                return;
            }

            btn.classList.add('is-loading');
            btn.disabled = true;
            const idle = btn.querySelector('.btn-signin-idle');
            const busy = btn.querySelector('.btn-signin-busy');
            if (idle) idle.classList.add('d-none');
            if (busy) {
                busy.classList.remove('d-none');
                busy.classList.add('d-inline-flex', 'align-items-center', 'gap-2');
            }
        });
    })();
</script>
@endpush
@endsection
