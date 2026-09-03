@extends('layouts.admin')

@section('title', 'Edit Training Batch')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit Training Batch</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.batches.show', $batch) }}">{{ $batch->batch_code }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
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

<form method="POST" action="{{ route('admin.batches.update', $batch) }}">
    @csrf
    @method('PUT')
    @include('admin.batches._form')
</form>
@endsection
