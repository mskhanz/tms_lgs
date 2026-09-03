@extends('layouts.admin')

@section('title', 'Edit Training Program')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit Training Program</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">Programs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.show', $program) }}">{{ $program->code }}</a></li>
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

<form method="POST" action="{{ route('admin.programs.update', $program) }}" id="programForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label fw-semibold">
                                Program Code <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="code"
                                   id="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $program->code) }}"
                                   placeholder="e.g., TP-2025-001"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="conducting_organization_id" class="form-label fw-semibold">
                                Conducting Organization
                            </label>
                            <select name="conducting_organization_id"
                                    id="conducting_organization_id"
                                    class="form-select @error('conducting_organization_id') is-invalid @enderror">
                                <option value="">-- Select Organization --</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}" {{ (string) old('conducting_organization_id', $program->conducting_organization_id) === (string) $org->id ? 'selected' : '' }}>
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('conducting_organization_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">
                            Program Title <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $program->title) }}"
                               placeholder="Enter program title"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea name="description"
                                  id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Detailed description of the training program..."
                                  required>{{ old('description', $program->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label fw-semibold">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category"
                                    id="category"
                                    class="form-select @error('category') is-invalid @enderror"
                                    required>
                                <option value="">-- Select Category --</option>
                                @foreach(['technical' => 'Technical', 'leadership' => 'Leadership', 'management' => 'Management', 'specialized' => 'Specialized', 'soft_skills' => 'Soft Skills', 'mid_career_training' => 'Mid Career Training', 'pre_service_training' => 'Pre-service Training', 'pre_promotion_training' => 'Pre-Promotion Training', 'others' => 'Others'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('category', $program->category) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label fw-semibold">
                                Type <span class="text-danger">*</span>
                            </label>
                            <select name="type"
                                    id="type"
                                    class="form-select @error('type') is-invalid @enderror"
                                    required>
                                <option value="">-- Select Type --</option>
                                @foreach(['orientation' => 'Orientation', 'induction' => 'Induction', 'refresher' => 'Refresher', 'specialized' => 'Specialized', 'advanced' => 'Advanced'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('type', $program->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="budget_allocated" class="form-label fw-semibold">
                                Budget Allocated (PKR)
                            </label>
                            <input type="number"
                                   name="budget_allocated"
                                   id="budget_allocated"
                                   class="form-control @error('budget_allocated') is-invalid @enderror"
                                   value="{{ old('budget_allocated', $program->budget_allocated) }}"
                                   min="0"
                                   step="0.01"
                                   placeholder="0.00">
                            @error('budget_allocated')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-clock me-2"></i>Duration & Participants</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="duration_days" class="form-label fw-semibold">
                                Duration (Days) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   name="duration_days"
                                   id="duration_days"
                                   class="form-control @error('duration_days') is-invalid @enderror"
                                   value="{{ old('duration_days', $program->duration_days) }}"
                                   min="1"
                                   required>
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="duration_hours" class="form-label fw-semibold">
                                Duration (Hours) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   name="duration_hours"
                                   id="duration_hours"
                                   class="form-control @error('duration_hours') is-invalid @enderror"
                                   value="{{ old('duration_hours', $program->duration_hours) }}"
                                   min="1"
                                   required>
                            @error('duration_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="min_participants" class="form-label fw-semibold">
                                Min Participants
                            </label>
                            <input type="number"
                                   name="min_participants"
                                   id="min_participants"
                                   class="form-control @error('min_participants') is-invalid @enderror"
                                   value="{{ old('min_participants', $program->min_participants) }}"
                                   min="1">
                            @error('min_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="max_participants" class="form-label fw-semibold">
                                Max Participants
                            </label>
                            <input type="number"
                                   name="max_participants"
                                   id="max_participants"
                                   class="form-control @error('max_participants') is-invalid @enderror"
                                   value="{{ old('max_participants', $program->max_participants) }}"
                                   min="1">
                            @error('max_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Additional Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="objectives" class="form-label fw-semibold">
                            Training Objectives
                        </label>
                        <textarea name="objectives"
                                  id="objectives"
                                  class="form-control @error('objectives') is-invalid @enderror"
                                  rows="4"
                                  placeholder="List the main objectives of this training program...">{{ old('objectives', $program->objectives) }}</textarea>
                        @error('objectives')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="target_audience" class="form-label fw-semibold">
                            Target Audience
                        </label>
                        <textarea name="target_audience"
                                  id="target_audience"
                                  class="form-control @error('target_audience') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Describe the target audience for this program...">{{ old('target_audience', $program->target_audience) }}</textarea>
                        @error('target_audience')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Update Program
                </button>
                <a href="{{ route('admin.programs.show', $program) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            @include('admin.partials.attendance-settings', [
                'model' => $program,
                'context' => 'program',
            ])

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-flag me-2"></i>Status</h6>
                </div>
                <div class="card-body">
                    <label for="status" class="form-label fw-semibold">
                        Program Status <span class="text-danger">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>
                        @foreach(['draft' => 'Draft', 'pending_approval' => 'Pending Approval', 'approved' => 'Approved', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'archived' => 'Archived', 'active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $program->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</form>

@push('styles')
<style>
    .form-label.fw-semibold {
        color: #047857;
    }
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #34d399 100%);
        transform: translateY(-1px);
    }
</style>
@endpush
@endsection
