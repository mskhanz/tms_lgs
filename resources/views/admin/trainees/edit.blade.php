@extends('layouts.admin')

@section('title', 'Edit Trainee - ' . $trainee->name)

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-pencil me-2"></i>Edit Trainee Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainees.index') }}">Trainees</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainees.show', $trainee->id) }}">{{ $trainee->name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Form -->
    <div class="col-lg-8">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Error:</strong> Please fix the following errors:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.trainees.update', $trainee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Picture -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-image me-2"></i>Profile Picture</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            @if($trainee->photo)
                                <img src="{{ asset('user_photos/' . $trainee->photo) }}" 
                                     alt="{{ $trainee->name }}" 
                                     class="rounded-circle mb-3"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #10b981;">
                            @elseif($trainee->traineeProfile && $trainee->traineeProfile->file_picture && file_exists(public_path('trainee_pictures/' . $trainee->traineeProfile->file_picture)))
                                <img src="{{ asset('trainee_pictures/' . $trainee->traineeProfile->file_picture) }}" 
                                     alt="{{ $trainee->name }}" 
                                     class="rounded-circle mb-3"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #10b981;">
                            @else
                                <div class="mx-auto mb-3" style="width: 120px; height: 120px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 600;">
                                    {{ strtoupper(substr($trainee->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Upload New Photo</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommended size: 500x500px, Max: 2MB</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $trainee->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Father Name</label>
                            <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror" 
                                   value="{{ old('father_name', $trainee->traineeProfile->father_name ?? '') }}">
                            @error('father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">CNIC</label>
                            <input type="text" name="cnic_no" class="form-control @error('cnic_no') is-invalid @enderror" 
                                   value="{{ old('cnic_no', $trainee->traineeProfile->cnic_no ?? '') }}"
                                   placeholder="00000-0000000-0">
                            @error('cnic_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $trainee->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $trainee->traineeProfile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $trainee->traineeProfile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $trainee->traineeProfile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" 
                                   value="{{ old('dob', $trainee->traineeProfile->dob ?? '') }}">
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_no" class="form-control @error('contact_no') is-invalid @enderror" 
                                   value="{{ old('contact_no', $trainee->traineeProfile->contact_no ?? '') }}"
                                   placeholder="03XX-XXXXXXX">
                            @error('contact_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Alternate Contact</label>
                            <input type="text" name="alt_contact_no" class="form-control @error('alt_contact_no') is-invalid @enderror" 
                                   value="{{ old('alt_contact_no', $trainee->traineeProfile->alt_contact_no ?? '') }}">
                            @error('alt_contact_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="text" name="whatsapp_no" class="form-control @error('whatsapp_no') is-invalid @enderror" 
                                   value="{{ old('whatsapp_no', $trainee->traineeProfile->whatsapp_no ?? '') }}">
                            @error('whatsapp_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-building me-2"></i>Employment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" 
                                   value="{{ old('designation', $trainee->traineeProfile->designation ?? '') }}">
                            @error('designation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">BPS</label>
                            <input type="number" name="bps" class="form-control @error('bps') is-invalid @enderror" 
                                   value="{{ old('bps', $trainee->traineeProfile->bps ?? '') }}"
                                   min="1" max="22">
                            @error('bps')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-toggle-on me-2"></i>Account Status</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="form-check-input" name="is_active" value="1" 
                               id="is_active" {{ old('is_active', $trainee->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Account Active
                        </label>
                    </div>
                    <small class="text-muted">Inactive accounts cannot log in</small>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="card mb-4">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Profile
                    </button>
                    <a href="{{ route('admin.trainees.show', $trainee->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Help Card -->
        <div class="card mb-4 border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Edit Tips</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li class="mb-2">Required fields are marked with <span class="text-danger">*</span></li>
                    <li class="mb-2">Email must be unique in the system</li>
                    <li class="mb-2">CNIC format: 00000-0000000-0</li>
                    <li class="mb-2">Profile picture max size: 2MB</li>
                    <li class="mb-2">Inactive accounts cannot login</li>
                    <li>All changes are logged for audit</li>
                </ul>
            </div>
        </div>

        <!-- Trainee Info -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Trainee Info</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <small class="text-muted">Account Created</small>
                    <p class="fw-semibold mb-0">{{ $trainee->created_at->format('d M, Y') }}</p>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <small class="text-muted">Last Updated</small>
                    <p class="fw-semibold mb-0">{{ $trainee->updated_at->format('d M, Y') }}</p>
                </div>
                <div>
                    <small class="text-muted">Total Enrollments</small>
                    <p class="fw-semibold mb-0">{{ $trainee->enrollments->count() }}</p>
                </div>
            </div>
        </div>
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
    .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }
</style>
@endpush
@endsection
