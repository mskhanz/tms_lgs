@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
@php
    $isIncomplete = ! $user->profile_completed || ! $profile;
@endphp

<div class="page-header no-print">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-person-badge me-2"></i>My Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('trainee.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('trainee.profile.edit') }}" class="btn {{ $isIncomplete ? 'btn-info px-4' : 'btn-success' }}">
                <i class="bi bi-pencil me-2"></i>Edit Profile
            </a>
            @unless($isIncomplete)
            <a href="{{ route('account.password.edit') }}" class="btn btn-outline-primary">
                <i class="bi bi-key me-2"></i>Change Password
            </a>
            <a href="{{ route('trainee.profile.pdf') }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
            </a>
            @endunless
            <button type="button" onclick="window.print()" class="btn {{ $isIncomplete ? 'btn-success px-4' : 'btn-primary' }}">
                <i class="bi bi-printer me-2"></i>{{ $isIncomplete ? 'Print Profile' : 'Print' }}
            </button>
        </div>
    </div>
</div>

@if($isIncomplete)
<div class="alert alert-warning d-flex align-items-start mb-4 no-print" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 mt-1 flex-shrink-0"></i>
    <div>
        <strong>Profile Incomplete</strong>
        <p class="mb-0 small">Every trainee is required to update their profile. Please complete your profile details from the <a href="{{ route('trainee.profile.edit') }}" class="alert-link">Edit Profile</a> page.</p>
    </div>
</div>

<div class="row g-4 trainee-profile-incomplete">
    <div class="col-lg-8">
        @include('trainee.profile.partials.enrollment-details', ['enrollments' => $enrollments ?? collect()])
    </div>
    <div class="col-lg-4">
        <div class="card trainee-quick-stats-card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted">Profile Status</span>
                    <span class="badge bg-warning text-dark">Incomplete</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted">Account Status</span>
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Joined Date</span>
                    <span class="fw-semibold">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($profile)
<article class="profile-sheet">
    <header class="profile-sheet-banner">
        <div class="profile-sheet-brand">
            <img src="{{ asset('images/kp-logo.png') }}" alt="KP logo" class="profile-sheet-logo" onerror="this.style.display='none'">
            <div>
                <div class="profile-sheet-kicker">Government of Khyber Pakhtunkhwa</div>
                <h2>{{ config('app.name') }}</h2>
                <p>{{ config('app.tagline') }} · Trainee Profile</p>
            </div>
        </div>
        <div class="profile-sheet-meta">
            <span>{{ now()->format('d M Y') }}</span>
            <span>Confidential</span>
        </div>
    </header>

    <section class="profile-identity">
        <div class="profile-photo">
            @if($profile->photoUrl())
                <img src="{{ $profile->photoUrl() }}" alt="{{ $profile->emp_name }}">
            @else
                <div class="profile-photo-fallback">{{ strtoupper(substr($profile->emp_name ?? $user->name, 0, 1)) }}</div>
            @endif
        </div>
        <div class="profile-identity-main">
            <h1>{{ $profile->emp_name ?? $user->name }}</h1>
            <p class="profile-role">
                {{ $profile->designation ?? 'Trainee' }}
                @if($profile->bps) · BPS-{{ $profile->bps }} @endif
            </p>
            <p class="profile-org">
                {{ $profile->organization->name ?? 'Organization not set' }}
                @if($profile->district) · {{ $profile->district->name }} @endif
            </p>
            <div class="profile-chips">
                <span class="chip {{ $user->is_active ? 'chip-success' : 'chip-muted' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                <span class="chip {{ $user->profile_completed ? 'chip-info' : 'chip-warn' }}">{{ $user->profile_completed ? 'Profile complete' : 'Profile incomplete' }}</span>
                @if($profile->cadre)<span class="chip chip-muted">{{ $profile->cadre }}</span>@endif
                @if($profile->trainee_type)<span class="chip chip-muted">{{ $profile->trainee_type }}</span>@endif
            </div>
        </div>
        <div class="profile-stats">
            <div class="stat-card">
                <span class="stat-label">Age</span>
                <strong>{{ $profile->ageLabel() }}</strong>
                <small>{{ $profile->dob ? $profile->dob->format('d M Y') : 'DOB not set' }}</small>
            </div>
            <div class="stat-card">
                <span class="stat-label">Length of service</span>
                <strong>{{ $profile->serviceLengthLabel() }}</strong>
                <small>
                    {{ $profile->date_of_initial_appointment ? 'Since '.$profile->date_of_initial_appointment->format('d M Y') : 'Appointment date not set' }}
                </small>
            </div>
        </div>
    </section>

    <section class="profile-section">
        <h3>Personal information</h3>
        <div class="profile-grid">
            <div class="field"><span>Full name</span><strong>{{ $profile->emp_name ?? 'N/A' }}</strong></div>
            <div class="field"><span>Father's name</span><strong>{{ $profile->father_name ?? 'N/A' }}</strong></div>
            <div class="field"><span>CNIC</span><strong>{{ $profile->cnic_no ?? 'N/A' }}</strong></div>
            <div class="field"><span>Personal number</span><strong>{{ $profile->personal_no ?? 'N/A' }}</strong></div>
            <div class="field"><span>Gender</span><strong>{{ $profile->gender ? ucfirst($profile->gender) : 'N/A' }}</strong></div>
            <div class="field"><span>Date of birth</span><strong>{{ $profile->dob ? $profile->dob->format('d M Y') : 'N/A' }}</strong></div>
            <div class="field"><span>Age</span><strong>{{ $profile->ageLabel() }}</strong></div>
            <div class="field"><span>Domicile</span><strong>{{ $profile->domicile ?? 'N/A' }}</strong></div>
        </div>
    </section>

    <section class="profile-section">
        <h3>Contact</h3>
        <div class="profile-grid">
            <div class="field"><span>Official email</span><strong>{{ $profile->emp_email ?? $user->email }}</strong></div>
            <div class="field"><span>Contact number</span><strong>{{ $profile->contact_no ?? 'N/A' }}</strong></div>
            <div class="field"><span>WhatsApp</span><strong>{{ $profile->emp_whatsapp_no ?? 'N/A' }}</strong></div>
            <div class="field field-wide"><span>Permanent address</span><strong>{{ $profile->permanent_address ?? 'N/A' }}</strong></div>
            <div class="field field-wide"><span>Current address</span><strong>{{ $profile->current_address ?? 'N/A' }}</strong></div>
        </div>
    </section>

    <section class="profile-section">
        <h3>Posting details</h3>
        <div class="profile-grid">
            <div class="field"><span>District</span><strong>{{ $profile->district->name ?? 'N/A' }}</strong></div>
            <div class="field"><span>Organization</span><strong>{{ $profile->organization->name ?? 'N/A' }}</strong></div>
            <div class="field"><span>Designation</span><strong>{{ $profile->designation ?? 'N/A' }}</strong></div>
            <div class="field"><span>BPS</span><strong>{{ $profile->bps ?? 'N/A' }}</strong></div>
            <div class="field"><span>Cadre</span><strong>{{ $profile->cadre ?? 'N/A' }}</strong></div>
            <div class="field"><span>Section</span><strong>{{ $profile->section->name ?? 'N/A' }}</strong></div>
            <div class="field"><span>Initial appointment</span><strong>{{ $profile->date_of_initial_appointment ? $profile->date_of_initial_appointment->format('d M Y') : 'N/A' }}</strong></div>
            <div class="field"><span>Length of service</span><strong>{{ $profile->serviceLengthLabel() }}</strong></div>
            <div class="field"><span>Posting from</span><strong>{{ $profile->from_date ? $profile->from_date->format('d M Y') : 'N/A' }}</strong></div>
            <div class="field"><span>Tehsil</span><strong>{{ $profile->tehsil->name ?? 'N/A' }}</strong></div>
        </div>
    </section>

    @if($profile->qualifications && $profile->qualifications->count())
    <section class="profile-section">
        <h3>Academic qualifications</h3>
        <div class="table-responsive">
            <table class="profile-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Degree</th>
                        <th>Subject</th>
                        <th>Institute</th>
                        <th>Country</th>
                        <th>Year</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profile->qualifications as $index => $qualification)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $qualification->degree->name ?? 'N/A' }}</td>
                        <td>{{ $qualification->subject->name ?? 'N/A' }}</td>
                        <td>{{ $qualification->institute ?? 'N/A' }}</td>
                        <td>{{ $qualification->country->name ?? 'N/A' }}</td>
                        <td>{{ $qualification->passing_year ?? 'N/A' }}</td>
                        <td>{{ $qualification->marks_percentage ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

    @if($profile->remarks)
    <section class="profile-section">
        <h3>Remarks</h3>
        <p class="profile-remarks">{{ $profile->remarks }}</p>
    </section>
    @endif

    <section class="profile-section no-print">
        <h3>Enrollment details</h3>
        @include('trainee.profile.partials.enrollment-details', [
            'enrollments' => $enrollments ?? collect(),
            'showCard' => false,
        ])
    </section>

    <footer class="profile-sheet-footer">
        {{ config('app.name') }}, Local Governance School, Government of Khyber Pakhtunkhwa · Generated {{ now()->format('d M Y, h:i A') }}
    </footer>
</article>
@endif
@endsection

@push('styles')
<style>
    .trainee-profile-incomplete .trainee-quick-stats-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06);
    }
    .trainee-profile-incomplete .card-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.25rem;
    }
    .trainee-profile-incomplete .card-body {
        padding: 1.25rem;
    }
    .trainee-profile-incomplete .badge.bg-warning {
        background-color: #fbbf24 !important;
        color: #1f2937 !important;
        font-weight: 600;
        padding: 0.45em 0.85em;
    }
    .trainee-enrollment-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06);
    }
    .trainee-profile-incomplete .trainee-enrollment-card {
        margin-top: 1rem;
    }
    .trainee-enrollment-card .enrollment-meta strong {
        font-weight: 600;
    }
    .profile-section .trainee-enrollment-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .trainee-enrollment-sheet .enrollment-item {
        padding: 0.85rem 0;
    }
    .trainee-enrollment-sheet .enrollment-item:first-child {
        padding-top: 0;
    }
    @media (max-width: 991.98px) {
        .trainee-profile-incomplete .col-lg-4 {
            max-width: 420px;
        }
    }
    .profile-sheet {
        background: #fff;
        border: 1px solid #e6ebe8;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }
    .profile-sheet-banner {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
        color: #fff;
    }
    .profile-sheet-brand {
        display: flex;
        align-items: center;
        gap: 0.9rem;
    }
    .profile-sheet-logo {
        width: 52px;
        height: 52px;
        object-fit: contain;
        background: #fff;
        border-radius: 50%;
        padding: 6px;
    }
    .profile-sheet-kicker {
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        opacity: 0.8;
    }
    .profile-sheet-banner h2 {
        font-size: 1.15rem;
        margin: 0.1rem 0;
        font-weight: 700;
    }
    .profile-sheet-banner p,
    .profile-sheet-meta {
        margin: 0;
        font-size: 0.85rem;
        opacity: 0.9;
    }
    .profile-sheet-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        gap: 0.2rem;
    }
    .profile-identity {
        display: grid;
        grid-template-columns: 110px 1fr 280px;
        gap: 1.25rem;
        padding: 1.5rem;
        border-bottom: 1px solid #eef2f0;
        align-items: center;
    }
    .profile-photo img,
    .profile-photo-fallback {
        width: 110px;
        height: 110px;
        border-radius: 18px;
        object-fit: cover;
        border: 3px solid #d1fae5;
    }
    .profile-photo-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ecfdf5;
        color: #047857;
        font-size: 2.4rem;
        font-weight: 700;
    }
    .profile-identity-main h1 {
        font-size: 1.7rem;
        margin: 0 0 0.2rem;
        color: #0f172a;
    }
    .profile-role, .profile-org {
        margin: 0;
        color: #475569;
    }
    .profile-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.75rem;
    }
    .chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.2rem 0.65rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .chip-success { background: #dcfce7; color: #166534; }
    .chip-info { background: #e0f2fe; color: #075985; }
    .chip-warn { background: #fef3c7; color: #92400e; }
    .chip-muted { background: #f1f5f9; color: #475569; }
    .profile-stats {
        display: grid;
        gap: 0.7rem;
    }
    .stat-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 0.9rem;
    }
    .stat-label {
        display: block;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 0.15rem;
    }
    .stat-card strong {
        display: block;
        font-size: 1.05rem;
        color: #0f172a;
    }
    .stat-card small {
        color: #64748b;
    }
    .profile-section {
        padding: 1.25rem 1.5rem 0.4rem;
    }
    .profile-section h3 {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #047857;
        border-bottom: 2px solid #d1fae5;
        padding-bottom: 0.45rem;
        margin-bottom: 1rem;
    }
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.9rem 1rem;
        margin-bottom: 0.85rem;
    }
    .field span {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.15rem;
    }
    .field strong {
        display: block;
        color: #0f172a;
        font-weight: 600;
        word-break: break-word;
    }
    .field-wide { grid-column: span 2; }
    .profile-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .table-responsive .profile-table {
            min-width: 640px;
        }
    }
    .profile-table th,
    .profile-table td {
        border: 1px solid #e2e8f0;
        padding: 0.55rem 0.7rem;
        font-size: 0.9rem;
    }
    .profile-table th {
        background: #f8fafc;
        color: #334155;
        font-weight: 600;
    }
    .profile-remarks {
        color: #334155;
        margin-bottom: 1rem;
    }
    .profile-sheet-footer {
        margin-top: 0.5rem;
        padding: 0.9rem 1.5rem 1.2rem;
        font-size: 0.78rem;
        color: #64748b;
        border-top: 1px solid #eef2f0;
    }
    @media (max-width: 991.98px) {
        .profile-identity {
            grid-template-columns: 1fr;
        }
        .profile-grid,
        .field-wide {
            grid-template-columns: 1fr 1fr;
        }
        .field-wide { grid-column: span 2; }
    }
    @media print {
        @page { size: A4; margin: 10mm; }
        body, .app-main { background: #fff !important; }
        .app-sidebar, .top-navbar, .app-footer, #sidebar-overlay,
        .page-header, .no-print, .alert { display: none !important; }
        .app-main-wrapper, .app-main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .profile-sheet {
            border: none;
            border-radius: 0;
            box-shadow: none;
        }
        .profile-sheet-banner,
        .chip, .stat-card, .profile-table th {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .profile-section { page-break-inside: avoid; }
    }
</style>
@endpush
