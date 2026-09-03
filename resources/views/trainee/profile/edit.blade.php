@extends('layouts.admin')

@section('title', 'Edit Profile')

@php
    $selectedDistrictId = old('district_id', $profile->district_id ?? '');
    $selectedOrganizationId = old('organization_id', $profile->organization_id ?? '');
    $provincialOrgs = $organizations->whereNull('district_id');
    $districtOrgs = $organizations->whereNotNull('district_id');
    $profilePhoto = null;
    if ($profile && $profile->file_picture && file_exists(public_path('trainee_pictures/' . $profile->file_picture))) {
        $profilePhoto = asset('trainee_pictures/' . $profile->file_picture);
    } elseif ($user->photo) {
        $profilePhoto = asset('user_photos/' . $user->photo);
    }
    $organizationCatalog = $organizations->map(function ($org) {
        return [
            'id' => $org->id,
            'name' => $org->name,
            'district_id' => $org->district_id,
        ];
    })->values();
    $defaultCountryId = optional($countries->firstWhere('code', 'PK'))->id
        ?? optional($countries->first())->id;
    if (old('qualifications') !== null) {
        $qualificationRows = old('qualifications');
    } elseif ($profile && $profile->qualifications->isNotEmpty()) {
        $qualificationRows = $profile->qualifications->map(function ($qualification) {
            return [
                'id' => $qualification->id,
                'degree_id' => $qualification->degree_id,
                'subject_id' => $qualification->subject_id,
                'institute' => $qualification->institute,
                'country_id' => $qualification->country_id,
                'passing_year' => $qualification->passing_year,
                'percentage_marks' => $qualification->percentage_marks,
            ];
        })->all();
    } else {
        $qualificationRows = [[
            'id' => '',
            'degree_id' => '',
            'subject_id' => '',
            'institute' => '',
            'country_id' => $defaultCountryId,
            'passing_year' => '',
            'percentage_marks' => '',
        ]];
    }
@endphp

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('trainee.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainee.profile.show') }}">Profile</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('trainee.profile.show') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Cancel
        </a>
    </div>
</div>

<form id="profileEditForm" action="{{ route('trainee.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card mb-4 border-info">
        <div class="card-header bg-info text-white py-2">
            <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Instructions</h6>
        </div>
        <div class="card-body py-2">
            <small class="text-muted">
                Fields marked with <span class="text-danger">*</span> are required.
                CNIC: 00000-0000000-0 · Contact: 03XX-XXXXXXX · Photo max 2MB.
                <span class="ms-3">|</span>
                <span class="ms-3">
                    <strong>Current Status:</strong> Profile
                    @if($user->profile_completed)
                        <span class="badge bg-success">Complete</span>
                    @else
                        <span class="badge bg-warning text-dark">Incomplete</span>
                    @endif
                    Account
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    Last Updated: {{ ($profile && $profile->updated_at) ? $profile->updated_at->format('M d, Y') : 'Never' }}
                </span>
            </small>
        </div>
    </div>

    <div class="card profile-edit-card">
        <div class="card-body p-4">
            <div class="profile-photo-row mb-4 pb-4">
                <div class="profile-avatar-wrap">
                    @if($profilePhoto)
                        <img src="{{ $profilePhoto }}" alt="Profile photo" class="profile-avatar">
                    @else
                        <div class="profile-avatar profile-avatar-fallback">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <label class="form-label">Profile picture</label>
                    <input type="file"
                           name="file_picture"
                           class="form-control @error('file_picture') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/jpg">
                    <div class="form-text">JPG or PNG, maximum 2MB.</div>
                    @error('file_picture')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h6 class="profile-section-title"><i class="bi bi-person me-2"></i>Personal information</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Full name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="emp_name"
                           value="{{ old('emp_name', $profile->emp_name ?? $user->name) }}"
                           class="form-control @error('emp_name') is-invalid @enderror"
                           required>
                    @error('emp_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Father's name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="father_name"
                           value="{{ old('father_name', $profile->father_name ?? '') }}"
                           class="form-control @error('father_name') is-invalid @enderror"
                           required>
                    @error('father_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">CNIC <span class="text-danger">*</span></label>
                    <input type="text"
                           id="cnic_no"
                           name="cnic_no"
                           value="{{ old('cnic_no', $profile->cnic_no ?? '') }}"
                           class="form-control @error('cnic_no') is-invalid @enderror"
                           placeholder="00000-0000000-0"
                           pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}"
                           title="Enter CNIC as 00000-0000000-0"
                           maxlength="15"
                           data-original-cnic="{{ $profile->cnic_no ?? '' }}"
                           required>
                    <div id="cnic-feedback" class="invalid-feedback" style="display: none;"></div>
                    @error('cnic_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Personal number</label>
                    <input type="text"
                           name="personal_no"
                           value="{{ old('personal_no', $profile->personal_no ?? '') }}"
                           class="form-control @error('personal_no') is-invalid @enderror">
                    @error('personal_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select select2 @error('gender') is-invalid @enderror" required>
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $profile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Date of birth <span class="text-danger">*</span></label>
                    <input type="date"
                           name="dob"
                           value="{{ old('dob', ($profile && $profile->dob) ? $profile->dob->format('Y-m-d') : '') }}"
                           class="form-control @error('dob') is-invalid @enderror"
                           required>
                    @error('dob')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Domicile</label>
                    <select name="domicile" class="form-select select2 @error('domicile') is-invalid @enderror">
                        <option value="">Select domicile</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->name }}" {{ old('domicile', $profile->domicile ?? '') == $district->name ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('domicile')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Trainee type</label>
                    <select name="trainee_type" class="form-select select2 @error('trainee_type') is-invalid @enderror">
                        <option value="">Select type</option>
                        <option value="PUGF" {{ old('trainee_type', $profile->trainee_type ?? '') == 'PUGF' ? 'selected' : '' }}>PUGF</option>
                        <option value="NON-PUGF" {{ old('trainee_type', $profile->trainee_type ?? '') == 'NON-PUGF' ? 'selected' : '' }}>NON-PUGF</option>
                    </select>
                    @error('trainee_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Cadre</label>
                    <select name="cadre" class="form-select select2 @error('cadre') is-invalid @enderror">
                        <option value="">Select cadre</option>
                        <option value="Admin" {{ old('cadre', $profile->cadre ?? '') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Engineering" {{ old('cadre', $profile->cadre ?? '') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        <option value="Finance" {{ old('cadre', $profile->cadre ?? '') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Other" {{ old('cadre', $profile->cadre ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('cadre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Initial appointment date</label>
                    <input type="date"
                           name="date_of_initial_appointment"
                           value="{{ old('date_of_initial_appointment', ($profile && $profile->date_of_initial_appointment) ? $profile->date_of_initial_appointment->format('Y-m-d') : '') }}"
                           class="form-control @error('date_of_initial_appointment') is-invalid @enderror">
                    @error('date_of_initial_appointment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h6 class="profile-section-title"><i class="bi bi-telephone me-2"></i>Contact information</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Official email <span class="text-danger">*</span></label>
                    <input type="email"
                           name="emp_email"
                           value="{{ old('emp_email', $profile->emp_email ?? $user->email) }}"
                           class="form-control @error('emp_email') is-invalid @enderror"
                           required>
                    @error('emp_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact number <span class="text-danger">*</span></label>
                    <input type="text"
                           name="contact_no"
                           value="{{ old('contact_no', $profile->contact_no ?? '') }}"
                           class="form-control @error('contact_no') is-invalid @enderror"
                           placeholder="03XX-XXXXXXX"
                           pattern="03[0-9]{2}-[0-9]{7}"
                           title="Enter contact number as 03XX-XXXXXXX"
                           maxlength="12"
                           required>
                    @error('contact_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Permanent address</label>
                    <textarea name="permanent_address" rows="2" class="form-control @error('permanent_address') is-invalid @enderror">{{ old('permanent_address', $profile->permanent_address ?? '') }}</textarea>
                    @error('permanent_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Current address</label>
                    <textarea name="current_address" rows="2" class="form-control @error('current_address') is-invalid @enderror">{{ old('current_address', $profile->current_address ?? '') }}</textarea>
                    @error('current_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h6 class="profile-section-title"><i class="bi bi-building me-2"></i>Posting details</h6>
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">District <span class="text-danger">*</span></label>
                    <select id="district_id"
                            name="district_id"
                            class="form-select select2 @error('district_id') is-invalid @enderror"
                            required>
                        <option value="">Select district</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ (string) $selectedDistrictId === (string) $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('district_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Organization <span class="text-danger">*</span></label>
                    <select id="organization_id"
                            name="organization_id"
                            class="form-select select2 @error('organization_id') is-invalid @enderror"
                            required
                            data-placeholder="Select organization">
                        <option value="">Select organization</option>
                        @if($provincialOrgs->isNotEmpty())
                            <optgroup label="Provincial organizations">
                                @foreach($provincialOrgs as $org)
                                    <option value="{{ $org->id }}"
                                            data-district-id=""
                                            {{ (string) $selectedOrganizationId === (string) $org->id ? 'selected' : '' }}>
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                        @if($districtOrgs->isNotEmpty())
                            <optgroup label="Tehsil Municipal Administrations">
                                @foreach($districtOrgs as $org)
                                    <option value="{{ $org->id }}"
                                            data-district-id="{{ $org->district_id }}"
                                            {{ (string) $selectedOrganizationId === (string) $org->id ? 'selected' : '' }}>
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    <div class="form-text">TMAs are filtered by the selected district.</div>
                    @error('organization_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                    <select name="designation" class="form-select select2 @error('designation') is-invalid @enderror" required>
                        <option value="">Select designation</option>
                        @foreach($designations as $desig)
                            <option value="{{ $desig->name }}" {{ old('designation', $profile->designation ?? '') == $desig->name ? 'selected' : '' }}>
                                {{ $desig->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">BPS <span class="text-danger">*</span></label>
                    <input type="number"
                           name="bps"
                           value="{{ old('bps', $profile->bps ?? '') }}"
                           class="form-control @error('bps') is-invalid @enderror"
                           min="1"
                           max="22"
                           required>
                    @error('bps')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">From date</label>
                    <input type="date"
                           name="from_date"
                           value="{{ old('from_date', ($profile && $profile->from_date) ? $profile->from_date->format('Y-m-d') : '') }}"
                           class="form-control @error('from_date') is-invalid @enderror">
                    @error('from_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">To date</label>
                    <input type="date"
                           id="to_date"
                           name="to_date"
                           value="{{ old('to_date') }}"
                           class="form-control @error('to_date') is-invalid @enderror">
                    @error('to_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label d-block">Posting status</label>
                    <div class="btn-group posting-status-group" role="group">
                        <input class="btn-check"
                               type="radio"
                               name="posting_status"
                               id="status_current"
                               value="1"
                               {{ old('posting_status', $profile->status ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="btn btn-outline-success" for="status_current">Current</label>
                        <input class="btn-check"
                               type="radio"
                               name="posting_status"
                               id="status_previous"
                               value="0"
                               {{ old('posting_status', $profile->status ?? '1') == '0' ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary" for="status_previous">Previous</label>
                    </div>
                    @error('posting_status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h6 class="profile-section-title mt-4"><i class="bi bi-mortarboard me-2"></i>Academic qualifications</h6>
            <p class="text-muted small mb-3">Add your degrees and certificates. Leave a row blank if you have nothing to add.</p>
            @error('qualifications')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror
            <div id="qualifications-list">
                @foreach($qualificationRows as $index => $row)
                    @include('trainee.profile.partials.qualification-row', [
                        'index' => $index,
                        'row' => $row,
                        'degrees' => $degrees,
                        'subjects' => $subjects,
                        'countries' => $countries,
                        'defaultCountryId' => $defaultCountryId,
                    ])
                @endforeach
            </div>
            <button type="button" id="add-qualification" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add qualification
            </button>
            <template id="qualification-row-template">
                @include('trainee.profile.partials.qualification-row', [
                    'index' => '__INDEX__',
                    'row' => [
                        'id' => '',
                        'degree_id' => '',
                        'subject_id' => '',
                        'institute' => '',
                        'country_id' => $defaultCountryId,
                        'passing_year' => '',
                        'percentage_marks' => '',
                    ],
                    'degrees' => $degrees,
                    'subjects' => $subjects,
                    'countries' => $countries,
                    'defaultCountryId' => $defaultCountryId,
                ])
            </template>
        </div>

        <div class="card-footer profile-edit-footer">
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-save me-2"></i>Update profile
            </button>
            <a href="{{ route('trainee.profile.show') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i>Cancel
            </a>
        </div>
    </div>
</form>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<style>
    .profile-edit-card {
        overflow: visible;
    }
    .profile-section-title {
        color: #334155;
        font-weight: 700;
        letter-spacing: 0.02em;
        margin: 0 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    .profile-photo-row {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }
    .profile-avatar,
    .profile-avatar-fallback {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #dee2e6;
        flex-shrink: 0;
    }
    .profile-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9ecef;
        color: #495057;
        font-size: 1.75rem;
        font-weight: 700;
    }
    .profile-edit-footer {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .posting-status-group .btn {
        padding: 0.5rem 1rem;
    }
    .qualification-row {
        background: #fbfcfd;
    }
    .select2-container {
        width: 100% !important;
    }
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 46px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 44px;
        padding-left: 15px;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 44px;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #ced4da;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.06);
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: #dee2e6;
        z-index: 1080;
    }
    .select2-container--bootstrap-5 .select2-search__field {
        border-radius: 6px !important;
        border-color: #ced4da !important;
    }
    .select2-container--bootstrap-5 .select2-search__field:focus {
        border-color: #adb5bd !important;
        box-shadow: none !important;
    }
    .select2-results__option--highlighted,
    .select2-container--bootstrap-5 .select2-results__option--highlighted,
    .select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #e9ecef !important;
        color: #212529 !important;
    }
    .select2-container--bootstrap-5 .select2-results__option--selected,
    .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
        background-color: #f1f3f5 !important;
        color: #212529 !important;
    }
    @media (max-width: 576px) {
        .profile-photo-row {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {
    const $district = $('#district_id');
    const $organization = $('#organization_id');
    const selectedOrganizationId = @json($selectedOrganizationId);
    const organizationCatalog = @json($organizationCatalog);

    function initSelect2($el) {
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }
        $el.select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $(document.body),
            placeholder: $el.data('placeholder') || $el.find('option[value=""]').first().text() || 'Select',
            allowClear: !$el.prop('required'),
            minimumResultsForSearch: 0
        });
    }

    $('.select2').not('#organization_id').each(function () {
        initSelect2($(this));
    });

    function filterOrganizations(keepSelection) {
        const districtId = String($district.val() || '');
        const previousValue = keepSelection
            ? String($organization.val() || selectedOrganizationId || '')
            : '';

        $organization.empty().append(new Option('Select organization', '', false, false));

        if (!districtId) {
            initSelect2($organization);
            $organization.val(null).trigger('change');
            return;
        }

        const provincial = organizationCatalog.filter(function (org) {
            return !org.district_id;
        });
        const tmas = organizationCatalog.filter(function (org) {
            return String(org.district_id) === districtId;
        });

        if (provincial.length) {
            const provincialGroup = $('<optgroup>', { label: 'Provincial organizations' });
            provincial.forEach(function (org) {
                provincialGroup.append(new Option(org.name, org.id, false, false));
            });
            $organization.append(provincialGroup);
        }

        if (tmas.length) {
            const tmaGroup = $('<optgroup>', { label: 'Tehsil Municipal Administrations' });
            tmas.forEach(function (org) {
                tmaGroup.append(new Option(org.name, org.id, false, false));
            });
            $organization.append(tmaGroup);
        }

        const stillValid = previousValue && $organization.find('option[value="' + previousValue + '"]').length;
        initSelect2($organization);
        $organization.val(stillValid ? previousValue : null).trigger('change');
    }

    $district.on('change', function () {
        filterOrganizations(false);
    });

    filterOrganizations(true);

    function handlePostingStatusChange() {
        const toDateInput = document.getElementById('to_date');
        const currentRadio = document.getElementById('status_current');
        if (!toDateInput || !currentRadio) {
            return;
        }
        if (currentRadio.checked) {
            toDateInput.value = '';
            toDateInput.disabled = true;
            toDateInput.style.backgroundColor = '#e9ecef';
        } else {
            toDateInput.disabled = false;
            toDateInput.style.backgroundColor = '';
        }
    }

    document.querySelectorAll('input[name="posting_status"]').forEach(function (radio) {
        radio.addEventListener('change', handlePostingStatusChange);
    });
    handlePostingStatusChange();

    const qualificationsList = document.getElementById('qualifications-list');
    const qualificationTemplate = document.getElementById('qualification-row-template');
    const addQualificationBtn = document.getElementById('add-qualification');
    let qualificationIndex = qualificationsList ? qualificationsList.querySelectorAll('.qualification-row').length : 0;

    function refreshQualificationLabels() {
        if (!qualificationsList) return;
        qualificationsList.querySelectorAll('.qualification-row').forEach(function (row, i) {
            const label = row.querySelector('.qualification-row-label');
            if (label) {
                label.textContent = 'Qualification ' + (i + 1);
            }
        });
    }

    addQualificationBtn?.addEventListener('click', function () {
        if (!qualificationTemplate || !qualificationsList) return;
        const html = qualificationTemplate.innerHTML.replaceAll('__INDEX__', String(qualificationIndex));
        qualificationsList.insertAdjacentHTML('beforeend', html);
        qualificationIndex += 1;
        refreshQualificationLabels();
    });

    qualificationsList?.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-qualification');
        if (!button) return;
        const row = button.closest('.qualification-row');
        if (row) {
            row.remove();
            refreshQualificationLabels();
        }
    });

    refreshQualificationLabels();
});

let cnicCheckTimeout;
const cnicInput = document.querySelector('#cnic_no');
const cnicFeedback = document.querySelector('#cnic-feedback');
const submitBtn = document.querySelector('button[type="submit"]');
const originalCnic = cnicInput?.dataset.originalCnic || '';

cnicInput?.addEventListener('input', function (e) {
    let value = e.target.value.replace(/[^0-9]/g, '');
    if (value.length > 13) value = value.slice(0, 13);

    let formatted = '';
    if (value.length > 0) formatted = value.slice(0, 5);
    if (value.length > 5) formatted += '-' + value.slice(5, 12);
    if (value.length > 12) formatted += '-' + value.slice(12, 13);

    e.target.value = formatted;

    if (formatted.length === 15 && formatted !== originalCnic) {
        clearTimeout(cnicCheckTimeout);
        cnicCheckTimeout = setTimeout(() => checkCnicDuplication(formatted), 500);
    } else {
        resetCnicValidation();
    }
});

function checkCnicDuplication(cnic) {
    fetch(`{{ route('trainee.profile.check-cnic') }}?cnic=${cnic}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            cnicInput.classList.add('is-invalid');
            cnicFeedback.textContent = 'This CNIC is already registered in the system.';
            cnicFeedback.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            resetCnicValidation();
        }
    })
    .catch(function () {});
}

function resetCnicValidation() {
    if (!cnicInput) return;
    cnicInput.classList.remove('is-invalid');
    if (cnicFeedback) cnicFeedback.style.display = 'none';
    if (submitBtn) submitBtn.disabled = false;
}

document.querySelector('input[name="contact_no"]')?.addEventListener('input', function (e) {
    let value = e.target.value.replace(/[^0-9]/g, '');
    if (value.length > 11) value = value.slice(0, 11);

    let formatted = '';
    if (value.length > 0) formatted = value.slice(0, 4);
    if (value.length > 4) formatted += '-' + value.slice(4, 11);

    e.target.value = formatted;
});
</script>
@endpush
