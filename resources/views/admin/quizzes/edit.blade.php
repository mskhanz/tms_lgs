@extends('layouts.admin')

@section('title', 'Edit Quiz')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit Quiz</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quizzes</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        @include('admin.quizzes._form', ['quiz' => $quiz])
    </div>
</div>
@endsection
