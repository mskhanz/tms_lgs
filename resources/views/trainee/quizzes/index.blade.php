@extends('layouts.admin')

@section('title', 'My Quizzes')

@section('content')
@php
    $quizzes = $quizzes ?? collect();
    $attempts = $attempts ?? collect();
@endphp

<div class="page-header">
    <h1><i class="bi bi-clipboard-check me-2"></i>My Quizzes</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('trainee.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Quizzes</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if(!empty($loadError))
<div class="alert alert-danger">
    <i class="bi bi-exclamation-octagon me-2"></i>
    <strong>Quizzes could not be loaded.</strong>
    <div class="small mt-1">{{ $loadError }}</div>
</div>
@endif

<div class="row g-4">
    @forelse($quizzes as $quiz)
    <div class="col-md-6 col-lg-4">
        @include('trainee.quizzes._card', ['quiz' => $quiz, 'attempts' => $attempts])
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle me-2"></i>
            No quizzes assigned to your enrollments at the moment.
            @if(\App\Models\Quiz::where('is_active', true)->exists())
            <div class="small mt-2 mb-0">
                Quizzes appear here only when you are enrolled in the assigned training program or batch.
                Contact your training officer if you believe this is incorrect.
            </div>
            @endif
        </div>
    </div>
    @endforelse
</div>
@endsection
