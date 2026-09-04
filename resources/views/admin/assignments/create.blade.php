@extends('layouts.admin')

@section('title', 'Create Assignment')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Create Assignment</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Assignments</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        @include('admin.assignments._form', ['assignment' => null])
    </div>
</div>
@endsection
