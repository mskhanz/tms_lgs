<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trainee Profile - {{ $profile->emp_name ?? $user->name }}</title>
    <style>
        @page { margin: 18px 22px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        .banner { background: #047857; color: #fff; padding: 12px 14px; }
        .banner table { width: 100%; }
        .brand-title { font-size: 16px; font-weight: bold; margin: 0; }
        .brand-sub { font-size: 10px; margin: 2px 0 0; }
        .meta { text-align: right; font-size: 10px; }
        .identity { width: 100%; margin-top: 12px; border-bottom: 1px solid #d1d5db; padding-bottom: 10px; }
        .photo { width: 82px; height: 90px; object-fit: cover; border: 1px solid #d1d5db; }
        .photo-fallback { width: 82px; height: 90px; background: #ecfdf5; text-align: center; line-height: 90px; font-size: 28px; color: #047857; font-weight: bold; }
        .name { font-size: 18px; margin: 0 0 4px; }
        .muted { color: #4b5563; margin: 0 0 3px; }
        .stat { background: #f3f4f6; border: 1px solid #e5e7eb; padding: 7px 8px; }
        .stat span { display: block; font-size: 8px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.4px; }
        .stat strong { display: block; font-size: 12px; margin-top: 2px; }
        h3 { font-size: 10px; text-transform: uppercase; letter-spacing: 0.7px; color: #047857; border-bottom: 1px solid #a7f3d0; padding-bottom: 4px; margin: 14px 0 8px; }
        .grid { width: 100%; }
        .grid td { width: 25%; vertical-align: top; padding: 0 8px 8px 0; }
        .label { display: block; font-size: 8px; color: #6b7280; text-transform: uppercase; }
        .value { display: block; font-size: 11px; font-weight: bold; margin-top: 2px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #d1d5db; padding: 5px 6px; text-align: left; }
        table.data th { background: #f3f4f6; font-size: 9px; }
        .footer { margin-top: 16px; font-size: 9px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 6px; }
    </style>
</head>
<body>
    <div class="banner">
        <table>
            <tr>
                <td>
                    <div class="brand-sub">Government of Khyber Pakhtunkhwa</div>
                    <div class="brand-title">{{ config('app.name') }}</div>
                    <div class="brand-sub">{{ config('app.tagline') }} · Trainee Profile</div>
                </td>
                <td class="meta">
                    {{ now()->format('d M Y') }}<br>
                    Confidential
                </td>
            </tr>
        </table>
    </div>

    <table class="identity">
        <tr>
            <td style="width: 95px; vertical-align: top;">
                @if($profile->photoDataUri())
                    <img src="{{ $profile->photoDataUri() }}" class="photo" alt="Photo">
                @else
                    <div class="photo-fallback">{{ strtoupper(substr($profile->emp_name ?? $user->name, 0, 1)) }}</div>
                @endif
            </td>
            <td style="vertical-align: top;">
                <p class="name">{{ $profile->emp_name ?? $user->name }}</p>
                <p class="muted">{{ $profile->designation ?? 'Trainee' }}@if($profile->bps) · BPS-{{ $profile->bps }}@endif</p>
                <p class="muted">{{ $profile->organization->name ?? 'Organization not set' }}@if($profile->district) · {{ $profile->district->name }}@endif</p>
                <p class="muted">{{ $user->is_active ? 'Active' : 'Inactive' }} · {{ $user->profile_completed ? 'Profile complete' : 'Profile incomplete' }}</p>
            </td>
            <td style="width: 210px; vertical-align: top;">
                <div class="stat" style="margin-bottom: 6px;">
                    <span>Age</span>
                    <strong>{{ $profile->ageLabel() }}</strong>
                    {{ $profile->dob ? $profile->dob->format('d M Y') : 'DOB not set' }}
                </div>
                <div class="stat">
                    <span>Length of service</span>
                    <strong>{{ $profile->serviceLengthLabel() }}</strong>
                    {{ $profile->date_of_initial_appointment ? 'Since '.$profile->date_of_initial_appointment->format('d M Y') : 'Appointment date not set' }}
                </div>
            </td>
        </tr>
    </table>

    <h3>Personal information</h3>
    <table class="grid">
        <tr>
            <td><span class="label">Full name</span><span class="value">{{ $profile->emp_name ?? 'N/A' }}</span></td>
            <td><span class="label">Father's name</span><span class="value">{{ $profile->father_name ?? 'N/A' }}</span></td>
            <td><span class="label">CNIC</span><span class="value">{{ $profile->cnic_no ?? 'N/A' }}</span></td>
            <td><span class="label">Personal number</span><span class="value">{{ $profile->personal_no ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Gender</span><span class="value">{{ $profile->gender ? ucfirst($profile->gender) : 'N/A' }}</span></td>
            <td><span class="label">Date of birth</span><span class="value">{{ $profile->dob ? $profile->dob->format('d M Y') : 'N/A' }}</span></td>
            <td><span class="label">Age</span><span class="value">{{ $profile->ageLabel() }}</span></td>
            <td><span class="label">Domicile</span><span class="value">{{ $profile->domicile ?? 'N/A' }}</span></td>
        </tr>
    </table>

    <h3>Contact</h3>
    <table class="grid">
        <tr>
            <td><span class="label">Official email</span><span class="value">{{ $profile->emp_email ?? $user->email }}</span></td>
            <td><span class="label">Contact number</span><span class="value">{{ $profile->contact_no ?? 'N/A' }}</span></td>
            <td><span class="label">WhatsApp</span><span class="value">{{ $profile->emp_whatsapp_no ?? 'N/A' }}</span></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Permanent address</span><span class="value">{{ $profile->permanent_address ?? 'N/A' }}</span></td>
            <td colspan="2"><span class="label">Current address</span><span class="value">{{ $profile->current_address ?? 'N/A' }}</span></td>
        </tr>
    </table>

    <h3>Posting details</h3>
    <table class="grid">
        <tr>
            <td><span class="label">District</span><span class="value">{{ $profile->district->name ?? 'N/A' }}</span></td>
            <td><span class="label">Organization</span><span class="value">{{ $profile->organization->name ?? 'N/A' }}</span></td>
            <td><span class="label">Designation</span><span class="value">{{ $profile->designation ?? 'N/A' }}</span></td>
            <td><span class="label">BPS</span><span class="value">{{ $profile->bps ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Cadre</span><span class="value">{{ $profile->cadre ?? 'N/A' }}</span></td>
            <td><span class="label">Initial appointment</span><span class="value">{{ $profile->date_of_initial_appointment ? $profile->date_of_initial_appointment->format('d M Y') : 'N/A' }}</span></td>
            <td><span class="label">Length of service</span><span class="value">{{ $profile->serviceLengthLabel() }}</span></td>
            <td><span class="label">Posting from</span><span class="value">{{ $profile->from_date ? $profile->from_date->format('d M Y') : 'N/A' }}</span></td>
        </tr>
    </table>

    @if($profile->qualifications && $profile->qualifications->count())
    <h3>Academic qualifications</h3>
    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>Degree</th>
                <th>Subject</th>
                <th>Institute</th>
                <th>Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profile->qualifications as $index => $qualification)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $qualification->degree->name ?? 'N/A' }}</td>
                <td>{{ $qualification->subject->name ?? 'N/A' }}</td>
                <td>{{ $qualification->institute ?? 'N/A' }}</td>
                <td>{{ $qualification->passing_year ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($profile->remarks)
    <h3>Remarks</h3>
    <p>{{ $profile->remarks }}</p>
    @endif

    <div class="footer">
        {{ config('app.name') }}, Local Governance School, Government of Khyber Pakhtunkhwa · Generated {{ now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
