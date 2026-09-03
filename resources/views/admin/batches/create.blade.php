@extends('layouts.admin')

@section('title', 'Create Training Batch')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Create Training Batch</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
            <li class="breadcrumb-item active">Create</li>
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

<form method="POST" action="{{ route('admin.batches.store') }}">
    @csrf
    @include('admin.batches._form', ['batch' => null])
</form>
@endsection
