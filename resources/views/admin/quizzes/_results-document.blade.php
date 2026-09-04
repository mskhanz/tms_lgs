@php
    $traineeName = $traineeName ?? function ($user) {
        return $user?->traineeProfile?->emp_name ?: ($user->name ?? '—');
    };
    $traineeOrg = $traineeOrg ?? function ($user) {
        return $user?->traineeProfile?->organization?->name ?: '—';
    };
@endphp

<div class="banner">
    <table>
        <tr>
            <td style="width: 52px; vertical-align: middle;">
                @if(!empty($logoSrc))
                    <img src="{{ $logoSrc }}" class="logo" alt="Logo">
                @elseif(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" class="logo" alt="Logo">
                @endif
            </td>
            <td>
                <div class="brand-kicker">Government of Khyber Pakhtunkhwa · Local Governance School</div>
                <div class="brand-title">{{ config('app.name') }}</div>
                <div class="brand-sub">{{ config('app.tagline') }}</div>
            </td>
            <td class="meta">
                <strong>Quiz Result Report</strong><br>
                {{ now()->format('d M Y, h:i A') }}
            </td>
        </tr>
    </table>
</div>

<table class="info">
    <tr>
        <td><span class="label">Quiz</span><span class="value">{{ $quiz->title }}</span></td>
        <td><span class="label">Assigned to</span><span class="value">{{ $quiz->assignmentLabel() }}</span></td>
        <td><span class="label">Passing score</span><span class="value">{{ $quiz->passing_percentage }}%</span></td>
        <td><span class="label">Duration</span><span class="value">{{ $quiz->duration_minutes ? $quiz->duration_minutes.' min' : 'No limit' }}</span></td>
    </tr>
</table>

<table class="stats">
    <tr>
        <td><b>{{ $stats['assigned'] }}</b><span>Assigned</span></td>
        <td><b>{{ $stats['attempted'] }}</b><span>Attempted</span></td>
        <td><b>{{ $stats['not_attempted'] }}</b><span>Not attempted</span></td>
        <td><b class="pass">{{ $stats['passed'] }}</b><span>Passed</span></td>
        <td><b class="fail">{{ $stats['failed'] }}</b><span>Failed</span></td>
        <td><b>{{ $stats['average'] }}%</b><span>Average</span></td>
    </tr>
</table>

@if($quiz->max_attempts > 1)
    <p class="note">Where a trainee has more than one attempt, the highest percentage is shown.</p>
@endif

<h3>1. Attempted trainees</h3>
<p class="sub">Ranked by percentage (highest first).</p>
<table class="data">
    <thead>
        <tr>
            <th style="width: 42px;">S. No</th>
            <th>Trainee</th>
            <th>Organization</th>
            <th style="width: 70px;">Score</th>
            <th style="width: 55px;">%</th>
            <th style="width: 70px;">Result</th>
            <th style="width: 120px;">Submitted</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attempted as $index => $attempt)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $traineeName($attempt->user) }}</td>
            <td>{{ $traineeOrg($attempt->user) }}</td>
            <td>{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</td>
            <td>{{ number_format((float) $attempt->percentage, 1) }}%</td>
            <td>
                <span class="badge {{ $attempt->passed ? 'badge-success' : 'badge-danger' }}">
                    {{ $attempt->passed ? 'Passed' : 'Failed' }}
                </span>
            </td>
            <td>{{ $attempt->submitted_at?->format('d M Y, h:i A') ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="empty">No completed attempts yet.</td></tr>
        @endforelse
    </tbody>
</table>

<h3>2. Not attempted</h3>
<p class="sub">Assigned trainees who have not submitted this quiz.</p>
<table class="data">
    <thead>
        <tr>
            <th style="width: 42px;">S. No</th>
            <th>Trainee</th>
            <th>Organization</th>
            <th style="width: 110px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($notAttempted as $index => $trainee)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $traineeName($trainee) }}</td>
            <td>{{ $traineeOrg($trainee) }}</td>
            <td>
                @if(in_array($trainee->id, $inProgressIds))
                    <span class="badge badge-warning">In progress</span>
                @else
                    <span class="badge badge-secondary">Not attempted</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="empty">
                {{ $stats['assigned'] ? 'All assigned trainees have attempted this quiz.' : 'No assigned trainees found for this quiz.' }}
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    {{ config('app.name') }}, Local Governance School, Government of Khyber Pakhtunkhwa
    · Generated {{ now()->format('d M Y, h:i A') }}
</div>
