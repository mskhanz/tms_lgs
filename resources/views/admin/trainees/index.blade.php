@extends('layouts.admin')

@section('title', 'Trainees Management')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-people me-2"></i>Trainees Management</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Trainees</li>
        </ol>
    </nav>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search by name, email, CNIC..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Search
                </button>
                <a href="{{ route('admin.trainees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Trainees List -->
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Trainees ({{ $trainees->total() }})</h5>
        <a href="{{ route('admin.users.create') }}?user_type=trainee" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-2"></i>Add New Trainee
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Trainee</th>
                        <th>CNIC</th>
                        <th>Contact</th>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainees as $trainee)
                    <tr>
                        <td>{{ $trainees->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($trainee->photo)
                                    <img src="{{ asset('user_photos/' . $trainee->photo) }}" 
                                         alt="{{ $trainee->name }}" 
                                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                                @elseif($trainee->traineeProfile && $trainee->traineeProfile->file_picture && file_exists(public_path('trainee_pictures/' . $trainee->traineeProfile->file_picture)))
                                    <img src="{{ asset('trainee_pictures/' . $trainee->traineeProfile->file_picture) }}" 
                                         alt="{{ $trainee->name }}" 
                                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                                @else
                                    <div style="width: 40px; height: 40px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; margin-right: 12px;">
                                        {{ strtoupper(substr($trainee->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $trainee->name }}</div>
                                    <small class="text-muted">{{ $trainee->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($trainee->traineeProfile)
                                {{ $trainee->traineeProfile->cnic_no ?? 'N/A' }}
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </td>
                        <td>
                            @if($trainee->traineeProfile)
                                {{ $trainee->traineeProfile->contact_no ?? 'N/A' }}
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </td>
                        <td>
                            @if($trainee->traineeProfile && $trainee->traineeProfile->organization)
                                {{ Str::limit($trainee->traineeProfile->organization->name, 30) }}
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $trainee->is_active ? 'success' : 'secondary' }}">
                                {{ $trainee->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($trainee->profile_completed)
                                <span class="badge bg-info">Complete</span>
                            @else
                                <span class="badge bg-warning">Incomplete</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.trainees.show', $trainee->id) }}" 
                               class="btn btn-sm btn-outline-primary"
                               title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.trainees.edit', $trainee->id) }}" 
                               class="btn btn-sm btn-outline-success"
                               title="Edit Profile">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">No trainees found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('admin.partials.pagination', ['paginator' => $trainees, 'label' => 'trainees'])
    </div>
</div>

@push('styles')
<style>
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }
</style>
@endpush
@endsection
