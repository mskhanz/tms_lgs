@extends('layouts.admin')

@section('title', 'Quiz Results')

@section('content')
@php
    $traineeName = function ($user) {
        return $user?->traineeProfile?->emp_name ?: ($user->name ?? '—');
    };
    $traineeOrg = function ($user) {
        return $user?->traineeProfile?->organization?->name ?: '—';
    };
    $traineeCnic = function ($user) {
        return $user?->traineeProfile?->cnic_no ?: '—';
    };
@endphp

<div class="page-header no-print">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-bar-chart me-2"></i>Quiz Result Report</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quizzes</a></li>
                    <li class="breadcrumb-item active">Results</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to quiz
            </a>
            <a href="{{ route('admin.quizzes.results.pdf', $quiz) }}" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
            </a>
            <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-printer me-1"></i>Print
            </button>
        </div>
    </div>
</div>

<div class="quiz-report">
    <div class="quiz-report-banner">
        <div class="quiz-report-brand">
            <img src="{{ asset('images/kp-logo.png') }}" alt="KP Logo" class="quiz-report-logo">
            <div>
                <div class="quiz-report-kicker">Government of Khyber Pakhtunkhwa · Local Governance School</div>
                <h2>{{ config('app.name') }}</h2>
                <p>{{ config('app.tagline') }}</p>
            </div>
        </div>
        <div class="quiz-report-meta">
            <strong>Quiz Result Report</strong>
            <span>{{ now()->format('d M Y, h:i A') }}</span>
        </div>
    </div>

    <div class="quiz-report-info">
        <div>
            <span>Quiz</span>
            <strong>{{ $quiz->title }}</strong>
        </div>
        <div>
            <span>Assigned to</span>
            <strong>{{ $quiz->assignmentLabel() }}</strong>
        </div>
        <div>
            <span>Passing score</span>
            <strong>{{ $quiz->passing_percentage }}%</strong>
        </div>
        <div>
            <span>Duration</span>
            <strong>{{ $quiz->duration_minutes ? $quiz->duration_minutes.' min' : 'No limit' }}</strong>
        </div>
    </div>

    <div class="quiz-report-stats">
        <div><b>{{ $stats['assigned'] }}</b><small>Assigned</small></div>
        <div><b>{{ $stats['attempted'] }}</b><small>Attempted</small></div>
        <div><b>{{ $stats['not_attempted'] }}</b><small>Not attempted</small></div>
        <div class="is-pass"><b>{{ $stats['passed'] }}</b><small>Passed</small></div>
        <div class="is-fail"><b>{{ $stats['failed'] }}</b><small>Failed</small></div>
        <div><b>{{ $stats['average'] }}%</b><small>Average</small></div>
    </div>

    @if($quiz->max_attempts > 1)
    <p class="quiz-report-note">Where a trainee has more than one attempt, the highest percentage is shown.</p>
    @endif

    <h3 class="quiz-report-section">1. Attempted trainees</h3>
    <p class="quiz-report-section-sub">Ranked by percentage (highest first).</p>
    <div class="table-responsive">
        <table class="table quiz-report-table mb-0">
            <thead>
                <tr>
                    <th style="width: 56px;">S. No</th>
                    <th>Trainee</th>
                    <th>Organization</th>
                    <th>Score</th>
                    <th>%</th>
                    <th>Result</th>
                    <th>Submitted</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempted as $index => $attempt)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $traineeName($attempt->user) }}</strong>
                        <div class="small text-muted">{{ $traineeCnic($attempt->user) }}</div>
                    </td>
                    <td>{{ $traineeOrg($attempt->user) }}</td>
                    <td>{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</td>
                    <td class="fw-semibold">{{ number_format((float) $attempt->percentage, 1) }}%</td>
                    <td>
                        <span class="badge bg-{{ $attempt->passed ? 'success' : 'danger' }}">
                            {{ $attempt->passed ? 'Passed' : 'Failed' }}
                        </span>
                    </td>
                    <td>{{ $attempt->submitted_at?->format('d M Y, h:i A') ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No completed attempts yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h3 class="quiz-report-section">2. Not attempted</h3>
    <p class="quiz-report-section-sub">Assigned trainees who have not submitted this quiz.</p>
    <div class="table-responsive">
        <table class="table quiz-report-table mb-0">
            <thead>
                <tr>
                    <th style="width: 56px;">S. No</th>
                    <th>Trainee</th>
                    <th>Organization</th>
                    <th>CNIC</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notAttempted as $index => $trainee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $traineeName($trainee) }}</strong></td>
                    <td>{{ $traineeOrg($trainee) }}</td>
                    <td>{{ $traineeCnic($trainee) }}</td>
                    <td>
                        @if(in_array($trainee->id, $inProgressIds))
                            <span class="badge bg-warning text-dark">In progress</span>
                        @else
                            <span class="badge bg-secondary">Not attempted</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        {{ $stats['assigned'] ? 'All assigned trainees have attempted this quiz.' : 'No assigned trainees found for this quiz.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="quiz-report-footer">
        {{ config('app.name') }}, Local Governance School, Government of Khyber Pakhtunkhwa
        · Generated {{ now()->format('d M Y, h:i A') }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .quiz-report {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .quiz-report-banner {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: flex-start;
        background: #047857;
        color: #fff;
        padding: 1.1rem 1.25rem;
    }
    .quiz-report-brand {
        display: flex;
        gap: 0.85rem;
        align-items: center;
    }
    .quiz-report-logo {
        width: 48px;
        height: 48px;
        object-fit: contain;
        background: #fff;
        border-radius: 50%;
        padding: 4px;
    }
    .quiz-report-kicker {
        font-size: 0.72rem;
        opacity: 0.9;
        letter-spacing: 0.02em;
    }
    .quiz-report-banner h2 {
        font-size: 1.2rem;
        margin: 0.1rem 0;
        font-weight: 700;
    }
    .quiz-report-banner p {
        margin: 0;
        font-size: 0.78rem;
        opacity: 0.9;
        max-width: 520px;
    }
    .quiz-report-meta {
        text-align: right;
        font-size: 0.82rem;
        white-space: nowrap;
    }
    .quiz-report-meta strong {
        display: block;
        font-size: 0.95rem;
        margin-bottom: 0.2rem;
    }
    .quiz-report-info {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        border-bottom: 1px solid #e2e8f0;
    }
    .quiz-report-info > div {
        padding: 0.85rem 1.1rem;
        border-right: 1px solid #e2e8f0;
    }
    .quiz-report-info > div:last-child { border-right: 0; }
    .quiz-report-info span {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 0.2rem;
    }
    .quiz-report-info strong {
        font-size: 0.92rem;
        color: #0f172a;
    }
    .quiz-report-stats {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .quiz-report-stats > div {
        padding: 0.85rem 0.75rem;
        text-align: center;
        border-right: 1px solid #e2e8f0;
    }
    .quiz-report-stats > div:last-child { border-right: 0; }
    .quiz-report-stats b {
        display: block;
        font-size: 1.2rem;
        line-height: 1.2;
        color: #0f172a;
    }
    .quiz-report-stats small {
        color: #64748b;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .quiz-report-stats .is-pass b { color: #047857; }
    .quiz-report-stats .is-fail b { color: #b91c1c; }
    .quiz-report-note {
        margin: 0;
        padding: 0.65rem 1.1rem;
        font-size: 0.82rem;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }
    .quiz-report-section {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #047857;
        margin: 1.1rem 1.1rem 0.15rem;
        padding-bottom: 0.35rem;
        border-bottom: 1px solid #a7f3d0;
    }
    .quiz-report-section-sub {
        margin: 0 1.1rem 0.65rem;
        font-size: 0.82rem;
        color: #64748b;
    }
    .quiz-report-table {
        margin: 0 0 0.5rem;
    }
    .quiz-report-table th {
        background: #f1f5f9;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #475569;
        white-space: nowrap;
    }
    .quiz-report-table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
    .quiz-report-footer {
        margin-top: 1rem;
        padding: 0.75rem 1.1rem;
        border-top: 1px solid #e2e8f0;
        font-size: 0.78rem;
        color: #64748b;
    }
    @media (max-width: 992px) {
        .quiz-report-info,
        .quiz-report-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        .quiz-report-info > div,
        .quiz-report-stats > div {
            border-bottom: 1px solid #e2e8f0;
        }
    }
    @media print {
        .no-print,
        .app-sidebar,
        .top-navbar,
        .app-footer,
        #sidebar-overlay,
        .alert {
            display: none !important;
        }
        .app-wrapper,
        .app-main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .quiz-report {
            border: 0;
            border-radius: 0;
        }
        .quiz-report-banner,
        .badge {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .quiz-report-table thead { display: table-header-group; }
        .quiz-report-section { break-after: avoid; }
        tr { break-inside: avoid; }
        body { background: #fff; }
    }
</style>
@endpush
