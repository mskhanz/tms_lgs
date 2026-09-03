@extends('layouts.admin')

@section('title', 'Edit Registration Training')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit Registration Training</h1>
</div>
<div class="card"><div class="card-body">@include('admin.registration-trainings._form', ['registrationTraining' => $registrationTraining])</div></div>
@endsection
