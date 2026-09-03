@extends('layouts.admin')

@section('title', 'Training Batches')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-collection me-2"></i>Training Batches</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">Programs</a></li>
            <li class="breadcrumb-item active">Batches</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.batches.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>New Batch
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.batches.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\TrainingBatch::statusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Program</label>
                <select name="training_program_id" class="form-select">
                    <option value="">All Programs</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}" {{ (string) request('training_program_id') === (string) $program->id ? 'selected' : '' }}>
                            {{ $program->code }} — {{ $program->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Batch code, venue, program..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Batch</th>
                        <th>Program</th>
                        <th>Dates</th>
                        <th>Venue</th>
                        <th>Seats</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $batch->batch_code }}</div>
                            <small class="text-muted">{{ $batch->enrollments_count }} enrolled</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $batch->trainingProgram->title ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $batch->trainingProgram->code ?? '' }}</small>
                        </td>
                        <td>
                            {{ $batch->start_date?->format('d M Y') }}
                            –
                            {{ $batch->end_date?->format('d M Y') }}
                        </td>
                        <td>{{ $batch->venue ?: 'N/A' }}</td>
                        <td>{{ $batch->seats_filled }} / {{ $batch->total_seats }}</td>
                        <td>
                            <span class="badge bg-{{ $batch->statusBadge() }}">{{ $batch->statusLabel() }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.batches.show', $batch->id) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.batches.edit', $batch->id) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete({{ $batch->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $batch->id }}" action="{{ route('admin.batches.destroy', $batch->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">No training batches found</p>
                            <a href="{{ route('admin.batches.create') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle me-2"></i>Create First Batch
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('admin.partials.pagination', ['paginator' => $batches, 'label' => 'batches'])
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(batchId) {
    if (confirm('Are you sure you want to delete this training batch?')) {
        document.getElementById('delete-form-' + batchId).submit();
    }
}
</script>
@endpush
@endsection
