@extends('layouts.admin')

@section('title', 'Trainee Dashboard')

@push('styles')
<style>
    .trainee-dashboard .dashboard-hero {
        background: linear-gradient(135deg, #047857 0%, #059669 45%, #10b981 100%);
        border-radius: 16px;
        color: #fff;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(5, 150, 105, 0.22);
        position: relative;
        overflow: hidden;
    }

    .trainee-dashboard .dashboard-hero::after {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .trainee-dashboard .dashboard-hero-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        position: relative;
        z-index: 1;
        flex-wrap: wrap;
    }

    .trainee-dashboard .dashboard-hero-user {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        min-width: 0;
    }

    .trainee-dashboard .dashboard-hero-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.45);
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .trainee-dashboard .dashboard-hero-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .trainee-dashboard .dashboard-hero-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.25;
        color: #fff;
    }

    .trainee-dashboard .dashboard-hero-subtitle {
        margin: 0.25rem 0 0;
        opacity: 0.92;
        font-size: 0.9rem;
    }

    .trainee-dashboard .dashboard-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.65rem;
    }

    .trainee-dashboard .dashboard-hero-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.22);
        font-size: 0.78rem;
        font-weight: 600;
    }

    .trainee-dashboard .dashboard-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
        position: relative;
        z-index: 1;
    }

    .trainee-dashboard .dashboard-hero-actions .btn {
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.45rem 0.95rem;
    }

    .trainee-dashboard .dashboard-hero-actions .btn-light {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        color: #047857;
    }

    .trainee-dashboard .dashboard-hero-actions .btn-outline-light {
        border-color: rgba(255, 255, 255, 0.65);
        color: #fff;
    }

    .trainee-dashboard .dashboard-hero-actions .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }

    .trainee-dashboard .trainee-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .trainee-dashboard .trainee-kpi-card {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 14px;
        padding: 1.1rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    .trainee-dashboard .trainee-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1);
        color: inherit;
    }

    .trainee-dashboard .trainee-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .trainee-dashboard .trainee-kpi-icon.emerald { background: #ecfdf5; color: #059669; }
    .trainee-dashboard .trainee-kpi-icon.blue { background: #eff6ff; color: #2563eb; }
    .trainee-dashboard .trainee-kpi-icon.amber { background: #fffbeb; color: #d97706; }
    .trainee-dashboard .trainee-kpi-icon.teal { background: #f0fdfa; color: #0d9488; }

    .trainee-dashboard .trainee-kpi-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.1;
        color: #0f172a;
    }

    .trainee-dashboard .trainee-kpi-label {
        display: block;
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.1rem;
    }

    .trainee-dashboard .trainee-panel {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        height: 100%;
    }

    .trainee-dashboard .trainee-panel-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }

    .trainee-dashboard .trainee-panel-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .trainee-dashboard .trainee-panel-link {
        font-size: 0.82rem;
        font-weight: 600;
        color: #059669;
        text-decoration: none;
    }

    .trainee-dashboard .trainee-panel-link:hover {
        color: #047857;
    }

    .trainee-dashboard .trainee-panel-body {
        padding: 1rem 1.25rem 1.25rem;
    }

    .trainee-dashboard .trainee-list-item {
        padding: 0.85rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .trainee-dashboard .trainee-list-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .trainee-dashboard .trainee-list-item:first-child {
        padding-top: 0;
    }

    .trainee-dashboard .trainee-profile-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 1rem;
    }

    .trainee-dashboard .trainee-profile-row {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.35rem 0;
        font-size: 0.88rem;
    }

    .trainee-dashboard .trainee-profile-row span:first-child {
        color: #64748b;
    }

    .trainee-dashboard .trainee-profile-row strong {
        color: #0f172a;
        text-align: right;
    }

    .trainee-dashboard .trainee-notification-item {
        padding: 0.85rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
        border-radius: 8px;
        padding-left: 0.35rem;
        padding-right: 0.35rem;
    }

    .trainee-dashboard .trainee-notification-item:hover {
        background: #f0fdf4;
    }

    .trainee-dashboard .trainee-notification-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .trainee-dashboard .trainee-empty {
        text-align: center;
        color: #94a3b8;
        padding: 1.5rem 0.5rem;
        font-size: 0.9rem;
    }

    .trainee-dashboard .trainee-attendance-overall-value {
        display: block;
        font-size: 1.75rem;
        font-weight: 700;
        color: #047857;
        line-height: 1.1;
    }

    .trainee-dashboard .trainee-attendance-overall-label {
        display: block;
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.15rem;
    }

    .trainee-dashboard .trainee-attendance-today-meta {
        background: #f8fafc;
        border: 1px solid #e8edf2;
        border-radius: 12px;
        padding: 0.9rem 1rem;
    }

    @media (max-width: 1199.98px) {
        .trainee-dashboard .trainee-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575.98px) {
        .trainee-dashboard .dashboard-hero {
            padding: 1rem;
            border-radius: 12px;
        }

        .trainee-dashboard .dashboard-hero-title {
            font-size: 1.25rem;
        }

        .trainee-dashboard .trainee-kpi-grid {
            grid-template-columns: 1fr;
        }

        .trainee-dashboard .dashboard-hero-actions .btn {
            flex: 1 1 calc(50% - 0.25rem);
            justify-content: center;
        }
    }

    .asg-countdown {
        display: inline-block;
        margin-left: 0.35rem;
        color: #dc2626;
        font-weight: 700;
        white-space: nowrap;
    }
    .asg-countdown.asg-countdown-overdue { color: #991b1b; }
</style>
@endpush

@section('content')
@php
    $heroPhoto = null;
    if ($traineeProfile && $traineeProfile->file_picture && file_exists(public_path('trainee_pictures/' . $traineeProfile->file_picture))) {
        $heroPhoto = asset('trainee_pictures/' . $traineeProfile->file_picture) . '?v=' . optional($traineeProfile->updated_at)->timestamp;
    } elseif ($user->photo && file_exists(public_path('user_photos/' . $user->photo))) {
        $heroPhoto = asset('user_photos/' . $user->photo);
    }
    $heroName = $traineeProfile?->emp_name ?? $user->name;
    $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100) : 0;

    $statusBadges = [
        'completed' => 'success',
        'in_progress' => 'primary',
        'enrolled' => 'warning text-dark',
        'dropped' => 'danger',
        'failed' => 'danger',
    ];

    $attendanceStatusLabels = [
        'present' => 'Present',
        'absent' => 'Absent',
        'late' => 'Late',
        'excused' => 'Excused',
        'not_marked' => 'Not marked',
    ];

    $attendanceStatusBadges = [
        'present' => 'success',
        'absent' => 'danger',
        'late' => 'warning text-dark',
        'excused' => 'info',
        'not_marked' => 'secondary',
    ];
@endphp

<div class="trainee-dashboard admin-dashboard">
    <div class="dashboard-hero">
        <div class="dashboard-hero-top">
            <div class="dashboard-hero-user">
                <div class="dashboard-hero-avatar">
                    @if($heroPhoto)
                        <img src="{{ $heroPhoto }}" alt="{{ $heroName }}">
                    @else
                        {{ strtoupper(substr($heroName, 0, 1)) }}
                    @endif
                </div>
                <div class="min-w-0">
                    <h1 class="dashboard-hero-title">Asalamoalikum! {{ $heroName }}</h1>
                    <p class="dashboard-hero-subtitle">Trainee Dashboard · {{ now()->format('l, F j, Y') }}</p>
                    <div class="dashboard-hero-meta">
                        <span class="dashboard-hero-chip">
                            <i class="bi bi-journal-text"></i>{{ number_format($totalEnrollments) }} enrollments
                        </span>
                        @if($overallAttendance !== null)
                        <span class="dashboard-hero-chip">
                            <i class="bi bi-calendar-check"></i>{{ number_format($overallAttendance, 1) }}% attendance
                        </span>
                        @endif
                        @if($unreadNotificationsCount > 0)
                        <a href="{{ route('notifications.index') }}" class="dashboard-hero-chip text-decoration-none text-white">
                            <i class="bi bi-bell"></i>{{ $unreadNotificationsCount }} new
                        </a>
                        @endif
                        <span class="dashboard-hero-chip">
                            <i class="bi bi-{{ $user->profile_completed ? 'check-circle' : 'exclamation-circle' }}"></i>
                            {{ $user->profile_completed ? 'Profile complete' : 'Profile incomplete' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-hero-actions">
            <a href="{{ route('trainee.quizzes.index') }}" class="btn btn-light">
                <i class="bi bi-clipboard-check me-1"></i>My Quizzes
            </a>
            <a href="{{ route('trainee.assignments.index') }}" class="btn btn-outline-light">
                <i class="bi bi-file-earmark-text me-1"></i>My Assignments
            </a>
            <a href="{{ route('trainee.attendance.index') }}" class="btn btn-outline-light">
                <i class="bi bi-calendar-check me-1"></i>My Attendance
            </a>
            <a href="{{ route('trainee.profile.show') }}" class="btn btn-outline-light">
                <i class="bi bi-person-badge me-1"></i>My Profile
            </a>
        </div>
    </div>

    @if(!$user->profile_completed)
    <div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 mt-1 flex-shrink-0"></i>
        <div>
            <strong>Profile Incomplete</strong>
            <p class="mb-0 small">Every trainee is required to update their profile. Please complete your profile details from the <a href="{{ route('trainee.profile.edit') }}" class="alert-link">Edit Profile</a> page.</p>
        </div>
    </div>
    @endif

    <div class="trainee-kpi-grid">
        <a href="{{ route('trainee.dashboard') }}" class="trainee-kpi-card">
            <div class="trainee-kpi-icon blue"><i class="bi bi-play-circle"></i></div>
            <div>
                <span class="trainee-kpi-value">{{ number_format($ongoingEnrollments) }}</span>
                <span class="trainee-kpi-label">Ongoing trainings</span>
            </div>
        </a>
        <a href="{{ route('trainee.dashboard') }}" class="trainee-kpi-card">
            <div class="trainee-kpi-icon emerald"><i class="bi bi-check-circle"></i></div>
            <div>
                <span class="trainee-kpi-value">{{ number_format($completedEnrollments) }}</span>
                <span class="trainee-kpi-label">Completed trainings</span>
            </div>
        </a>
        <a href="{{ route('trainee.dashboard') }}" class="trainee-kpi-card">
            <div class="trainee-kpi-icon amber"><i class="bi bi-award"></i></div>
            <div>
                <span class="trainee-kpi-value">{{ number_format($certificates) }}</span>
                <span class="trainee-kpi-label">Certificates earned</span>
            </div>
        </a>
        <a href="{{ route('trainee.quizzes.index') }}" class="trainee-kpi-card">
            <div class="trainee-kpi-icon teal"><i class="bi bi-clipboard-check"></i></div>
            <div>
                <span class="trainee-kpi-value">{{ $openQuizzesCount ?? 0 }}</span>
                <span class="trainee-kpi-label">Open quizzes</span>
            </div>
        </a>
        <a href="{{ route('trainee.assignments.index') }}" class="trainee-kpi-card">
            <div class="trainee-kpi-icon amber"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <span class="trainee-kpi-value">{{ $openAssignmentsCount ?? 0 }}</span>
                <span class="trainee-kpi-label">Open assignments</span>
            </div>
        </a>
    </div>

    <!-- Available Quizzes -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 py-3">
            <h5 class="mb-0"><i class="bi bi-clipboard-check me-2 text-success"></i>Available Quizzes</h5>
            <a href="{{ route('trainee.quizzes.index') }}" class="btn btn-sm btn-outline-primary align-self-sm-center">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            @if(!empty($quizLoadError))
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-octagon me-2"></i>
                <strong>Quizzes could not be loaded.</strong>
                <div class="small mt-1">{{ $quizLoadError }}</div>
            </div>
            @endif

            @if($availableQuizzes->count() > 0)
            <div class="row g-3">
                @foreach($availableQuizzes as $quiz)
                <div class="col-md-6 col-lg-4">
                    @include('trainee.quizzes._card', ['quiz' => $quiz, 'attempts' => $quizAttempts, 'cardClass' => 'border rounded h-100'])
                </div>
                @endforeach
            </div>
            @else
            <p class="text-muted text-center mb-0 py-3">No active quizzes at the moment.</p>
            @endif
        </div>
    </div>

    <!-- Available Assignments -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 py-3">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2 text-success"></i>Assignments</h5>
            <a href="{{ route('trainee.assignments.index') }}" class="btn btn-sm btn-outline-primary align-self-sm-center">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            @if(!empty($assignmentLoadError))
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-octagon me-2"></i>
                <strong>Assignments could not be loaded.</strong>
                <div class="small mt-1">{{ $assignmentLoadError }}</div>
            </div>
            @endif

            @php
                $availableAssignments = $availableAssignments ?? collect();
                $assignmentSubmissions = $assignmentSubmissions ?? collect();
            @endphp

            @if($availableAssignments->count() > 0)
            <div class="row g-3">
                @foreach($availableAssignments->take(6) as $assignment)
                <div class="col-md-6 col-lg-4">
                    @include('trainee.assignments._card', [
                        'assignment' => $assignment,
                        'submissions' => $assignmentSubmissions,
                        'cardClass' => 'border rounded h-100',
                    ])
                </div>
                @endforeach
            </div>
            @else
            <p class="text-muted text-center mb-0 py-3">No assignments at the moment.</p>
            @endif
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="trainee-panel">
                <div class="trainee-panel-header">
                    <h5><i class="bi bi-pie-chart me-2 text-success"></i>My Training Summary</h5>
                </div>
                <div class="trainee-panel-body">
                    <div class="dashboard-mini-stats mb-3">
                        <div class="dashboard-mini-stat">
                            <strong class="text-primary">{{ number_format($ongoingEnrollments) }}</strong>
                            <span>In progress</span>
                        </div>
                        <div class="dashboard-mini-stat">
                            <strong class="text-success">{{ number_format($completedEnrollments) }}</strong>
                            <span>Completed</span>
                        </div>
                        <div class="dashboard-mini-stat">
                            <strong class="text-warning">{{ number_format($enrolledCount) }}</strong>
                            <span>Enrolled</span>
                        </div>
                        <div class="dashboard-mini-stat">
                            <strong class="text-secondary">{{ number_format($certificates) }}</strong>
                            <span>Certificates</span>
                        </div>
                    </div>
                    <p class="dashboard-section-title mb-2">Completion progress</p>
                    <div class="dashboard-progress-item mb-0">
                        <div class="dashboard-progress-head">
                            <span>Training completion</span>
                            <span>{{ $completionRate }}%</span>
                        </div>
                        <div class="dashboard-progress">
                            <div class="dashboard-progress-bar" style="width: {{ $completionRate }}%"></div>
                        </div>
                    </div>
                    @if($overallAttendance !== null)
                    <div class="dashboard-progress-item mt-3 mb-0">
                        <div class="dashboard-progress-head">
                            <span>Attendance ({{ $presentCount }}/{{ $totalSessions }} sessions)</span>
                            <span>{{ number_format($overallAttendance, 1) }}%</span>
                        </div>
                        <div class="dashboard-progress">
                            <div class="dashboard-progress-bar" style="width: {{ $overallAttendance }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="trainee-panel">
                <div class="trainee-panel-header">
                    <h5><i class="bi bi-clock-history me-2 text-success"></i>Recent Enrollments</h5>
                    <a href="{{ route('trainee.profile.show') }}" class="trainee-panel-link">View profile</a>
                </div>
                <div class="trainee-panel-body">
                    @forelse($recentEnrollments as $enrollment)
                    <div class="trainee-list-item">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold small">{{ $enrollment->trainingBatch->trainingProgram->title ?? 'N/A' }}</div>
                                <div class="text-muted small">
                                    {{ $enrollment->trainingBatch->batch_code ?? 'N/A' }}
                                    @if($enrollment->trainingBatch?->start_date && $enrollment->trainingBatch?->end_date)
                                    · {{ $enrollment->trainingBatch->start_date->format('d M Y') }} – {{ $enrollment->trainingBatch->end_date->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                            <span class="badge bg-{{ $statusBadges[$enrollment->status] ?? 'secondary' }}">
                                {{ ucwords(str_replace('_', ' ', $enrollment->status)) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="trainee-empty">No enrollments yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="trainee-panel">
                <div class="trainee-panel-header">
                    <h5><i class="bi bi-calendar-check me-2 text-success"></i>Today's Attendance</h5>
                    <a href="{{ route('trainee.attendance.index') }}" class="trainee-panel-link">View all</a>
                </div>
                <div class="trainee-panel-body">
                    <div class="trainee-attendance-today-meta mb-3">
                        <div class="text-muted small">{{ $today->format('l, d M Y') }}</div>
                        @if($overallAttendance !== null)
                        <div class="trainee-attendance-overall mt-2">
                            <span class="trainee-attendance-overall-value">{{ number_format($overallAttendance, 1) }}%</span>
                            <span class="trainee-attendance-overall-label">Overall attendance</span>
                            <div class="small text-muted mt-1">{{ $presentCount }}/{{ $totalSessions }} marked sessions</div>
                        </div>
                        <div class="dashboard-progress mt-2">
                            <div class="dashboard-progress-bar" style="width: {{ $overallAttendance }}%"></div>
                        </div>
                        @endif
                    </div>

                    @if($todayAttendanceRows->count() > 0)
                        @foreach($todayAttendanceRows as $row)
                        <div class="trainee-list-item">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="fw-semibold small">{{ $row->program->title ?? 'Training' }}</div>
                                    <div class="text-muted small">
                                        {{ $row->session->title }}
                                        @if($row->session->sessionType)
                                            · {{ $row->session->sessionType->name }}
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        @if($row->session->start_time && $row->session->end_time)
                                            {{ \Carbon\Carbon::parse($row->session->start_time)->format('h:i A') }}
                                            –
                                            {{ \Carbon\Carbon::parse($row->session->end_time)->format('h:i A') }}
                                        @else
                                            Time not set
                                        @endif
                                        @if($row->check_in_time)
                                            · Check-in {{ $row->check_in_time->format('h:i A') }}
                                        @endif
                                    </div>
                                </div>
                                <span class="badge bg-{{ $attendanceStatusBadges[$row->status] ?? 'secondary' }}">
                                    {{ $attendanceStatusLabels[$row->status] ?? ucfirst($row->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @elseif($attendanceBatches > 0)
                        <div class="trainee-empty py-3">
                            No sessions scheduled for today.
                            @if($overallAttendance !== null)
                            <div class="small mt-1">Your overall attendance is {{ number_format($overallAttendance, 1) }}%.</div>
                            @endif
                        </div>
                    @else
                        <div class="trainee-empty py-3">Attendance is not available for your trainings yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="trainee-panel">
                <div class="trainee-panel-header">
                    <h5><i class="bi bi-bell me-2 text-success"></i>Recent Notifications</h5>
                    <div class="d-flex align-items-center gap-2">
                        @if($unreadNotificationsCount > 0)
                        <span class="badge bg-danger badge-blink">{{ $unreadNotificationsCount }} unread</span>
                        @endif
                        <a href="{{ route('notifications.index') }}" class="trainee-panel-link">View all</a>
                    </div>
                </div>
                <div class="trainee-panel-body">
                    @forelse($notifications as $notification)
                    <a href="{{ route('notifications.read', $notification) }}" class="trainee-notification-item text-decoration-none text-body d-block">
                        <div class="fw-semibold small">{{ $notification->title }}</div>
                        <div class="text-muted small">{{ $notification->message }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </div>
                    </a>
                    @empty
                    <div class="trainee-empty">No new notifications.</div>
                    @endforelse
                </div>
            </div>
        </div>

        @if($traineeProfile)
        <div class="col-lg-5">
            <div class="trainee-panel">
                <div class="trainee-panel-header">
                    <h5><i class="bi bi-person-badge me-2 text-success"></i>Profile Summary</h5>
                    <a href="{{ route('trainee.profile.show') }}" class="trainee-panel-link">Full profile</a>
                </div>
                <div class="trainee-panel-body">
                    <div class="trainee-profile-card">
                        <div class="trainee-profile-row">
                            <span>Name</span>
                            <strong>{{ $traineeProfile->emp_name ?? $user->name }}</strong>
                        </div>
                        <div class="trainee-profile-row">
                            <span>Organization</span>
                            <strong>{{ $traineeProfile->organization->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="trainee-profile-row">
                            <span>Designation</span>
                            <strong>
                                {{ $traineeProfile->designation ?? 'N/A' }}
                                @if($traineeProfile->bps) · BPS-{{ $traineeProfile->bps }} @endif
                            </strong>
                        </div>
                        <div class="trainee-profile-row">
                            <span>District</span>
                            <strong>{{ $traineeProfile->district->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="trainee-profile-row">
                            <span>Contact</span>
                            <strong>{{ $traineeProfile->contact_no ?? 'N/A' }}</strong>
                        </div>
                    </div>
                    @unless($user->profile_completed)
                    <a href="{{ route('trainee.profile.edit') }}" class="btn btn-success btn-sm w-100 mt-3">
                        <i class="bi bi-pencil me-1"></i>Complete Profile
                    </a>
                    @endunless
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@include('assignments._due-countdown-script')
@endsection
