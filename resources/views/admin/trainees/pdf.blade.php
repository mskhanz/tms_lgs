<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trainee Dossier - {{ $profile->emp_name ?? $trainee->name }}</title>
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
        h4 { font-size: 11px; margin: 10px 0 6px; color: #111827; }
        .grid { width: 100%; }
        .grid td { width: 25%; vertical-align: top; padding: 0 8px 8px 0; }
        .label { display: block; font-size: 8px; color: #6b7280; text-transform: uppercase; }
        .value { display: block; font-size: 11px; font-weight: bold; margin-top: 2px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        table.data th, table.data td { border: 1px solid #d1d5db; padding: 5px 6px; text-align: left; font-size: 10px; }
        table.data th { background: #f3f4f6; font-size: 9px; }
        .enrollment-box { border: 1px solid #d1d5db; padding: 8px 10px; margin-bottom: 10px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-secondary { background: #f3f4f6; color: #374151; }
        .footer { margin-top: 16px; font-size: 9px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 6px; }
    </style>
</head>
<body>
    @php
        $statusLabels = [
            'present' => 'Present',
            'absent' => 'Absent',
            'late' => 'Late',
            'excused' => 'Excused',
            'not_marked' => 'Not marked',
        ];
    @endphp

    <div class="banner">
        <table>
            <tr>
                <td>
                    <div class="brand-sub">Government of Khyber Pakhtunkhwa</div>
                    <div class="brand-title">{{ config('app.name') }}</div>
                    <div class="brand-sub">{{ config('app.tagline') }} · Trainee Dossier</div>
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
                @if($profile && $profile->photoDataUri())
                    <img src="{{ $profile->photoDataUri() }}" class="photo" alt="Photo">
                @else
                    <div class="photo-fallback">{{ strtoupper(substr($profile->emp_name ?? $trainee->name, 0, 1)) }}</div>
                @endif
            </td>
            <td style="vertical-align: top;">
                <p class="name">{{ $profile->emp_name ?? $trainee->name }}</p>
                <p class="muted">{{ $profile->designation ?? 'Trainee' }}@if($profile?->bps) · BPS-{{ $profile->bps }}@endif</p>
                <p class="muted">{{ $profile->organization->name ?? 'Organization not set' }}@if($profile?->district) · {{ $profile->district->name }}@endif</p>
                <p class="muted">{{ $trainee->email }} · {{ $trainee->is_active ? 'Active' : 'Inactive' }}</p>
            </td>
            <td style="width: 210px; vertical-align: top;">
                <div class="stat" style="margin-bottom: 6px;">
                    <span>Enrollments</span>
                    <strong>{{ $enrollmentSummaries->count() }}</strong>
                </div>
                <div class="stat">
                    <span>Overall attendance</span>
                    <strong>{{ $attendanceOverview['totalSessions'] > 0 ? number_format($attendanceOverview['overallPercentage'], 1).'%' : 'N/A' }}</strong>
                    {{ $attendanceOverview['presentCount'] }}/{{ $attendanceOverview['totalSessions'] }} marked sessions
                </div>
            </td>
        </tr>
    </table>

    @if($profile)
    <h3>Personal information</h3>
    <table class="grid">
        <tr>
            <td><span class="label">Full name</span><span class="value">{{ $profile->emp_name ?? 'N/A' }}</span></td>
            <td><span class="label">Father's name</span><span class="value">{{ $profile->father_name ?? 'N/A' }}</span></td>
            <td><span class="label">CNIC</span><span class="value">{{ $profile->cnic_no ?? 'N/A' }}</span></td>
            <td><span class="label">Contact</span><span class="value">{{ $profile->contact_no ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Gender</span><span class="value">{{ $profile->gender ? ucfirst($profile->gender) : 'N/A' }}</span></td>
            <td><span class="label">Date of birth</span><span class="value">{{ $profile->dob ? $profile->dob->format('d M Y') : 'N/A' }}</span></td>
            <td><span class="label">District</span><span class="value">{{ $profile->district->name ?? 'N/A' }}</span></td>
            <td><span class="label">Tehsil</span><span class="value">{{ $profile->tehsil->name ?? 'N/A' }}</span></td>
        </tr>
    </table>

    <h3>Employment details</h3>
    @php
        $designationBps = $profile->designation ?? 'N/A';
        if ($profile->bps) {
            $designationBps .= ' · BPS-'.$profile->bps;
        }
    @endphp
    <table class="data">
        <thead>
            <tr>
                <th>Organization</th>
                <th>Designation / BPS</th>
                <th>Cadre</th>
                <th>Initial appointment</th>
                <th>Service length</th>
                <th>From date</th>
                <th>District</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $profile->organization->name ?? 'N/A' }}</td>
                <td>{{ $designationBps }}</td>
                <td>{{ $profile->cadre ?? 'N/A' }}</td>
                <td>{{ $profile->date_of_initial_appointment ? $profile->date_of_initial_appointment->format('d M Y') : 'N/A' }}</td>
                <td>{{ $profile->serviceLengthLabel() }}</td>
                <td>{{ $profile->from_date ? $profile->from_date->format('d M Y') : 'N/A' }}</td>
                <td>{{ $profile->district->name ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>

    <h3>Academic qualifications</h3>
    @if($profile->qualifications && $profile->qualifications->count())
    <table class="data">
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
    @else
    <p class="muted">No qualifications recorded.</p>
    @endif

    @if($profile->remarks)
    <h3>Remarks</h3>
    <p>{{ $profile->remarks }}</p>
    @endif
    @else
    <p class="muted">Profile is incomplete. Limited information is available.</p>
    @endif

    <h3>Enrollments ({{ $enrollmentSummaries->count() }})</h3>
    @forelse($enrollmentSummaries as $summary)
        @php
            $enrollment = $summary->enrollment;
            $batch = $summary->batch;
            $program = $summary->program;
            $minRequired = $batch?->effectiveMinAttendancePercentage();
        @endphp
        <div class="enrollment-box">
            <h4>{{ $program->title ?? 'Training not set' }} · {{ $batch->batch_code ?? 'N/A' }}</h4>
            <table class="grid">
                <tr>
                    <td><span class="label">Status</span><span class="value">{{ ucwords(str_replace('_', ' ', $enrollment->status)) }}</span></td>
                    <td><span class="label">Enrolled</span><span class="value">{{ $enrollment->enrollment_date?->format('d M Y') ?? 'N/A' }}</span></td>
                    <td><span class="label">Batch status</span><span class="value">{{ $batch?->statusLabel() ?? 'N/A' }}</span></td>
                    <td><span class="label">Venue</span><span class="value">{{ $batch->venue ?? 'N/A' }}</span></td>
                </tr>
                @if($batch?->start_date && $batch?->end_date)
                <tr>
                    <td colspan="2"><span class="label">Training dates</span><span class="value">{{ $batch->start_date->format('d M Y') }} – {{ $batch->end_date->format('d M Y') }}</span></td>
                    @if($summary->showAttendance)
                    <td colspan="2"><span class="label">Attendance</span><span class="value">{{ number_format((float) $enrollment->attendance_percentage, 1) }}%@if($minRequired !== null) (required {{ $minRequired }}%)@endif</span></td>
                    @endif
                </tr>
                @endif
            </table>

            @if($summary->showAttendance && $summary->sessionRows->count())
            <table class="data">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Session</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Check-in</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary->sessionRows as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->session->title }}</td>
                        <td>{{ $row->session->session_date?->format('d M Y') }}</td>
                        <td>{{ $statusLabels[$row->status] ?? ucfirst($row->status) }}</td>
                        <td>{{ $row->record?->check_in_time?->format('h:i A') ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    @empty
        <p class="muted">No enrollments recorded.</p>
    @endforelse

    <div class="footer">
        {{ config('app.name') }}, Local Governance School, Government of Khyber Pakhtunkhwa · Generated {{ now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
