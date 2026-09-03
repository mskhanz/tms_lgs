@extends('layouts.admin')

@section('title', 'Create Training Program')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Create Training Program</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">Programs</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<!-- Alerts -->
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

<form method="POST" action="{{ route('admin.programs.store') }}" id="programForm">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information Card -->
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
                                   value="{{ old('code') }}"
                                   placeholder="e.g., TP-2025-001"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Unique identifier for this program</div>
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
                                    <option value="{{ $org->id }}" {{ old('conducting_organization_id') == $org->id ? 'selected' : '' }}>
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
                               value="{{ old('title') }}"
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
                                  required>{{ old('description') }}</textarea>
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
                                <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                <option value="leadership" {{ old('category') == 'leadership' ? 'selected' : '' }}>Leadership</option>
                                <option value="management" {{ old('category') == 'management' ? 'selected' : '' }}>Management</option>
                                <option value="specialized" {{ old('category') == 'specialized' ? 'selected' : '' }}>Specialized</option>
                                <option value="soft_skills" {{ old('category') == 'soft_skills' ? 'selected' : '' }}>Soft Skills</option>
                                <option value="mid_career_training" {{ old('category') == 'mid_career_training' ? 'selected' : '' }}>Mid Career Training</option>
                                <option value="pre_service_training" {{ old('category') == 'pre_service_training' ? 'selected' : '' }}>Pre-service Training</option>
                                <option value="pre_promotion_training" {{ old('category') == 'pre_promotion_training' ? 'selected' : '' }}>Pre-Promotion Training</option>
                                <option value="others" {{ old('category') == 'others' ? 'selected' : '' }}>Others</option>
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
                                <option value="orientation" {{ old('type') == 'orientation' ? 'selected' : '' }}>Orientation</option>
                                <option value="induction" {{ old('type') == 'induction' ? 'selected' : '' }}>Induction</option>
                                <option value="refresher" {{ old('type') == 'refresher' ? 'selected' : '' }}>Refresher</option>
                                <option value="specialized" {{ old('type') == 'specialized' ? 'selected' : '' }}>Specialized</option>
                                <option value="advanced" {{ old('type') == 'advanced' ? 'selected' : '' }}>Advanced</option>
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
                                   value="{{ old('budget_allocated') }}"
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

            <!-- Duration & Participants Card -->
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
                                   value="{{ old('duration_days') }}"
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
                                   value="{{ old('duration_hours') }}"
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
                                   value="{{ old('min_participants') }}"
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
                                   value="{{ old('max_participants') }}"
                                   min="1">
                            @error('max_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details Card -->
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
                                  placeholder="List the main objectives of this training program...">{{ old('objectives') }}</textarea>
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
                                  placeholder="Describe the target audience for this program...">{{ old('target_audience') }}</textarea>
                        @error('target_audience')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Create Program
                </button>
                <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            @include('admin.partials.attendance-settings', [
                'model' => $program ?? null,
                'context' => 'program',
            ])

            <!-- Help Card -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-question-circle me-2"></i>Program Creation Guide</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary"><i class="bi bi-lightbulb me-1"></i>Tips:</h6>
                    <ul class="small mb-3">
                        <li>Use a unique, descriptive program code</li>
                        <li>Provide clear and detailed objectives</li>
                        <li>Specify realistic duration and participant limits</li>
                        <li>Choose appropriate category and type</li>
                    </ul>

                    <h6 class="text-success"><i class="bi bi-info-circle me-1"></i>After Creation:</h6>
                    <ul class="small mb-0">
                        <li>Program will be in "Draft" status</li>
                        <li>Create training batches for enrollment</li>
                        <li>Assign trainers to batches</li>
                        <li>Activate program when ready</li>
                    </ul>
                </div>
            </div>

            <!-- Categories Info -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-tags me-2"></i>Categories Guide</h6>
                </div>
                <div class="card-body small">
                    <div class="mb-2">
                        <strong>Technical:</strong> IT, software, hardware, systems
                    </div>
                    <div class="mb-2">
                        <strong>Leadership:</strong> Management, decision-making
                    </div>
                    <div class="mb-2">
                        <strong>Management:</strong> Administration, planning
                    </div>
                    <div class="mb-2">
                        <strong>Specialized:</strong> Domain-specific skills
                    </div>
                    <div class="mb-2">
                        <strong>Soft Skills:</strong> Communication, teamwork
                    </div>
                    <div class="mb-2">
                        <strong>Mid Career Training:</strong> In-service officers at mid career stage
                    </div>
                    <div class="mb-2">
                        <strong>Pre-service Training:</strong> New inductees before assuming duties
                    </div>
                    <div class="mb-2">
                        <strong>Pre-Promotion Training:</strong> Officers preparing for the next grade
                    </div>
                    <div>
                        <strong>Others:</strong> Miscellaneous programs
                    </div>
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
    .card-header.bg-info {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%) !important;
    }
</style>
@endpush
@endsection
