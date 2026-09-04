@extends('layouts.admin')

@section('title', 'My Assignments')

@push('styles')
<style>
    .asg-countdown {
        display: inline-block;
        margin-left: 0.35rem;
        color: #dc2626;
        font-weight: 700;
        white-space: nowrap;
    }
    .asg-countdown.asg-countdown-overdue { color: #991b1b; }
</style>
@endpush

@section('content')
@php
    $assignments = $assignments ?? collect();
    $submissions = $submissions ?? collect();
@endphp

<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2"></i>My Assignments</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('trainee.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Assignments</li>
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
    <strong>Assignments could not be loaded.</strong>
    <div class="small mt-1">{{ $loadError }}</div>
</div>
@endif

<div class="row g-4">
    @forelse($assignments as $assignment)
    <div class="col-md-6 col-lg-4">
        @include('trainee.assignments._card', ['assignment' => $assignment, 'submissions' => $submissions])
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle me-2"></i>
            No assignments assigned to your enrollments at the moment.
        </div>
    </div>
    @endforelse
</div>

@include('assignments._due-countdown-script')
@endsection
