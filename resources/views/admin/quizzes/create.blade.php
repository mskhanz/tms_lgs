@extends('layouts.admin')

@section('title', 'Create Quiz')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Create Quiz</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quizzes</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        @include('admin.quizzes._form', ['quiz' => null])
    </div>
</div>
@endsection
