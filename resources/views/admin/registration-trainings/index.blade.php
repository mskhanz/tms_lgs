@extends('layouts.admin')

@section('title', 'Registration Trainings')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-mortarboard me-2"></i>Registration Training Options</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Registration Trainings</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-between mb-4">
    <p class="text-muted mb-0">These options appear on the trainee registration page.</p>
    <a href="{{ route('admin.registration-trainings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Add Training Option
    </a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Training Title</th>
                    <th>Trainees</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $training)
                <tr>
                    <td>{{ $training->sort_order }}</td>
                    <td>
                        <strong>{{ $training->title }}</strong>
                        @if($training->description)<br><small class="text-muted">{{ Str::limit($training->description, 80) }}</small>@endif
                    </td>
                    <td><span class="badge bg-info">{{ $training->trainees_count }}</span></td>
                    <td><span class="badge bg-{{ $training->is_active ? 'success' : 'secondary' }}">{{ $training->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.registration-trainings.edit', $training) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.registration-trainings.destroy', $training) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this training option?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No registration training options yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        @include('admin.partials.pagination', ['paginator' => $trainings, 'label' => 'training options'])
    </div>
</div>
@endsection
