@extends('layouts.admin')

@section('title', 'Edit Profile')

@php
    $photo = $user->photoUrl();
@endphp

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Profile</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('account.profile') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Cancel
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">
                        @if($photo)
                            <img src="{{ $photo }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 88px; height: 88px; object-fit: cover; border: 3px solid #dee2e6;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width: 88px; height: 88px; background: #10b981; font-size: 1.75rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <label class="form-label">Profile photo</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text">JPG or PNG, maximum 2MB.</div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full name <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        <div class="form-text">Email cannot be changed from here. Contact an administrator if it needs updating.</div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-2"></i>Save changes
                    </button>
                    <a href="{{ route('account.profile') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
