@extends('layouts.admin')

@section('title', 'Add Registration Training')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Add Registration Training</h1>
</div>
<div class="card"><div class="card-body">@include('admin.registration-trainings._form')</div></div>
@endsection
