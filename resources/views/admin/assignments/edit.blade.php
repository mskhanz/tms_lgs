@extends('layouts.admin')

@section('title', 'Edit Assignment')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit Assignment</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Assignments</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        @include('admin.assignments._form', ['assignment' => $assignment])
    </div>
</div>
@endsection
