

<?php $__env->startSection('title', 'Trainee Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
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
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
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
?>

<div class="trainee-dashboard admin-dashboard">
    <div class="dashboard-hero">
        <div class="dashboard-hero-top">
            <div class="dashboard-hero-user">
                <div class="dashboard-hero-avatar">
                    <?php if($heroPhoto): ?>
                        <img src="<?php echo e($heroPhoto); ?>" alt="<?php echo e($heroName); ?>">
                    <?php else: ?>
                        <?php echo e(strtoupper(substr($heroName, 0, 1))); ?>

                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <h1 class="dashboard-hero-title">Asalamoalikum! <?php echo e($heroName); ?></h1>
                    <p class="dashboard-hero-subtitle">Trainee Dashboard · <?php echo e(now()->format('l, F j, Y')); ?></p>
                    <div class="dashboard-hero-meta">
                        <span class="dashboard-hero-chip">
                            <i class="bi bi-journal-text"></i><?php echo e(number_format($totalEnrollments)); ?> enrollments
                        </span>
                        <?php if($overallAttendance !== null): ?>
                        <span class="dashboard-hero-chip">
                            <i class="bi bi-calendar-check"></i><?php echo e(number_format($overallAttendance, 1)); ?>% attendance
                        </span>
                        <?php endif; ?>
                        <?php if($unreadNotificationsCount > 0): ?>
                        <a href="<?php echo e(route('notifications.index')); ?>" class="dashboard-hero-chip text-decoration-none text-white">
                            <i class="bi bi-bell"></i><?php echo e($unreadNotificationsCount); ?> new
                        </a>
                        <?php endif; ?>
                        <span class="dashboard-hero-chip">
                            <i class="bi bi-<?php echo e($user->profile_completed ? 'check-circle' : 'exclamation-circle'); ?>"></i>
                            <?php echo e($user->profile_completed ? 'Profile complete' : 'Profile incomplete'); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-hero-actions">
            <a href="<?php echo e(route('trainee.quizzes.index')); ?>" class="btn btn-light">
                <i class="bi bi-clipboard-check me-1"></i>My Quizzes
            </a>
            <a href="<?php echo e(route('trainee.assignments.index')); ?>" class="btn btn-outline-light">
                <i class="bi bi-file-earmark-text me-1"></i>My Assignments
            </a>
            <a href="<?php echo e(route('trainee.attendance.index')); ?>" class="btn btn-outline-light">
                <i class="bi bi-calendar-check me-1"></i>My Attendance
            </a>
            <a href="<?php echo e(route('trainee.profile.show')); ?>" class="btn btn-outline-light">
                <i class="bi bi-person-badge me-1"></i>My Profile
            </a>
        </div>
    </div>

    <?php if(!$user->profile_completed): ?>
    <div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 mt-1 flex-shrink-0"></i>
        <div>
            <strong>Profile Incomplete</strong>
            <p class="mb-0 small">Every trainee is required to update their profile. Please complete your profile details from the <a href="<?php echo e(route('trainee.profile.edit')); ?>" class="alert-link">Edit Profile</a> page.</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="trainee-kpi-grid">
        <a href="<?php echo e(route('trainee.dashboard')); ?>" class="trainee-kpi-card">
            <div class="trainee-kpi-icon blue"><i class="bi bi-play-circle"></i></div>
            <div>
                <span class="trainee-kpi-value"><?php echo e(number_format($ongoingEnrollments)); ?></span>
                <span class="trainee-kpi-label">Ongoing trainings</span>
            </div>
        </a>
        <a href="<?php echo e(route('trainee.dashboard')); ?>" class="trainee-kpi-card">
            <div class="trainee-kpi-icon emerald"><i class="bi bi-check-circle"></i></div>
            <div>
                <span class="trainee-kpi-value"><?php echo e(number_format($completedEnrollments)); ?></span>
                <span class="trainee-kpi-label">Completed trainings</span>
            </div>
        </a>
        <a href="<?php echo e(route('trainee.dashboard')); ?>" class="trainee-kpi-card">
            <div class="trainee-kpi-icon amber"><i class="bi bi-award"></i></div>
            <div>
                <span class="trainee-kpi-value"><?php echo e(number_format($certificates)); ?></span>
                <span class="trainee-kpi-label">Certificates earned</span>
            </div>
        </a>
        <a href="<?php echo e(route('trainee.quizzes.index')); ?>" class="trainee-kpi-card">
            <div class="trainee-kpi-icon teal"><i class="bi bi-clipboard-check"></i></div>
            <div>
                <span class="trainee-kpi-value"><?php echo e($openQuizzesCount ?? 0); ?></span>
                <span class="trainee-kpi-label">Open quizzes</span>
            </div>
        </a>
        <a href="<?php echo e(route('trainee.assignments.index')); ?>" class="trainee-kpi-card">
            <div class="trainee-kpi-icon amber"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <span class="trainee-kpi-value"><?php echo e($openAssignmentsCount ?? 0); ?></span>
                <span class="trainee-kpi-label">Open assignments</span>
            </div>
        </a>
    </div>

    <!-- Available Quizzes -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 py-3">
            <h5 class="mb-0"><i class="bi bi-clipboard-check me-2 text-success"></i>Available Quizzes</h5>
            <a href="<?php echo e(route('trainee.quizzes.index')); ?>" class="btn btn-sm btn-outline-primary align-self-sm-center">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            <?php if(!empty($quizLoadError)): ?>
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-octagon me-2"></i>
                <strong>Quizzes could not be loaded.</strong>
                <div class="small mt-1"><?php echo e($quizLoadError); ?></div>
            </div>
            <?php endif; ?>

            <?php if($availableQuizzes->count() > 0): ?>
            <div class="row g-3">
                <?php $__currentLoopData = $availableQuizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <?php echo $__env->make('trainee.quizzes._card', ['quiz' => $quiz, 'attempts' => $quizAttempts, 'cardClass' => 'border rounded h-100'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <p class="text-muted text-center mb-0 py-3">No active quizzes at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Available Assignments -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 py-3">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2 text-success"></i>Assignments</h5>
            <a href="<?php echo e(route('trainee.assignments.index')); ?>" class="btn btn-sm btn-outline-primary align-self-sm-center">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            <?php if(!empty($assignmentLoadError)): ?>
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-octagon me-2"></i>
                <strong>Assignments could not be loaded.</strong>
                <div class="small mt-1"><?php echo e($assignmentLoadError); ?></div>
            </div>
            <?php endif; ?>

            <?php
                $availableAssignments = $availableAssignments ?? collect();
                $assignmentSubmissions = $assignmentSubmissions ?? collect();
            ?>

            <?php if($availableAssignments->count() > 0): ?>
            <div class="row g-3">
                <?php $__currentLoopData = $availableAssignments->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <?php echo $__env->make('trainee.assignments._card', [
                        'assignment' => $assignment,
                        'submissions' => $assignmentSubmissions,
                        'cardClass' => 'border rounded h-100',
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <p class="text-muted text-center mb-0 py-3">No assignments at the moment.</p>
            <?php endif; ?>
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
                            <strong class="text-primary"><?php echo e(number_format($ongoingEnrollments)); ?></strong>
                            <span>In progress</span>
                        </div>
                        <div class="dashboard-mini-stat">
                            <strong class="text-success"><?php echo e(number_format($completedEnrollments)); ?></strong>
                            <span>Completed</span>
                        </div>
                        <div class="dashboard-mini-stat">
                            <strong class="text-warning"><?php echo e(number_format($enrolledCount)); ?></strong>
                            <span>Enrolled</span>
                        </div>
                        <div class="dashboard-mini-stat">
                            <strong class="text-secondary"><?php echo e(number_format($certificates)); ?></strong>
                            <span>Certificates</span>
                        </div>
                    </div>
                    <p class="dashboard-section-title mb-2">Completion progress</p>
                    <div class="dashboard-progress-item mb-0">
                        <div class="dashboard-progress-head">
                            <span>Training completion</span>
                            <span><?php echo e($completionRate); ?>%</span>
                        </div>
                        <div class="dashboard-progress">
                            <div class="dashboard-progress-bar" style="width: <?php echo e($completionRate); ?>%"></div>
                        </div>
                    </div>
                    <?php if($overallAttendance !== null): ?>
                    <div class="dashboard-progress-item mt-3 mb-0">
                        <div class="dashboard-progress-head">
                            <span>Attendance (<?php echo e($presentCount); ?>/<?php echo e($totalSessions); ?> sessions)</span>
                            <span><?php echo e(number_format($overallAttendance, 1)); ?>%</span>
                        </div>
                        <div class="dashboard-progress">
                            <div class="dashboard-progress-bar" style="width: <?php echo e($overallAttendance); ?>%"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="trainee-panel">
                <div class="trainee-panel-header">
                    <h5><i class="bi bi-clock-history me-2 text-success"></i>Recent Enrollments</h5>
                    <a href="<?php echo e(route('trainee.profile.show')); ?>" class="trainee-panel-link">View profile</a>
                </div>
                <div class="trainee-panel-body">
                    <?php $__empty_1 = true; $__currentLoopData = $recentEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="trainee-list-item">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold small"><?php echo e($enrollment->trainingBatch->trainingProgram->title ?? 'N/A'); ?></div>
                                <div class="text-muted small">
                                    <?php echo e($enrollment->trainingBatch->batch_code ?? 'N/A'); ?>

                                    <?php if($enrollment->trainingBatch?->start_date && $enrollment->trainingBatch?->end_date): ?>
                                    · <?php echo e($enrollment->trainingBatch->start_date->format('d M Y')); ?> – <?php echo e($enrollment->trainingBatch->end_date->format('d M Y')); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="badge bg-<?php echo e($statusBadges[$enrollment->status] ?? 'secondary'); ?>">
                                <?php echo e(ucwords(str_replace('_', ' ', $enrollment->status))); ?>

                            </span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="trainee-empty">No enrollments yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="trainee-panel">
                <div class="trainee-panel-header">
                    <h5><i class="bi bi-calendar-check me-2 text-success"></i>Today's Attendance</h5>
                    <a href="<?php echo e(route('trainee.attendance.index')); ?>" class="trainee-panel-link">View all</a>
                </div>
                <div class="trainee-panel-body">
                    <div class="trainee-attendance-today-meta mb-3">
                        <div class="text-muted small"><?php echo e($today->format('l, d M Y')); ?></div>
                        <?php if($overallAttendance !== null): ?>
                        <div class="trainee-attendance-overall mt-2">
                            <span class="trainee-attendance-overall-value"><?php echo e(number_format($overallAttendance, 1)); ?>%</span>
                            <span class="trainee-attendance-overall-label">Overall attendance</span>
                            <div class="small text-muted mt-1"><?php echo e($presentCount); ?>/<?php echo e($totalSessions); ?> marked sessions</div>
                        </div>
                        <div class="dashboard-progress mt-2">
                            <div class="dashboard-progress-bar" style="width: <?php echo e($overallAttendance); ?>%"></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if($todayAttendanceRows->count() > 0): ?>
                        <?php $__currentLoopData = $todayAttendanceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="trainee-list-item">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="fw-semibold small"><?php echo e($row->program->title ?? 'Training'); ?></div>
                                    <div class="text-muted small">
                                        <?php echo e($row->session->title); ?>

                                        <?php if($row->session->sessionType): ?>
                                            · <?php echo e($row->session->sessionType->name); ?>

                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php if($row->session->start_time && $row->session->end_time): ?>
                                            <?php echo e(\Carbon\Carbon::parse($row->session->start_time)->format('h:i A')); ?>

                                            –
                                            <?php echo e(\Carbon\Carbon::parse($row->session->end_time)->format('h:i A')); ?>

                                        <?php else: ?>
                                            Time not set
                                        <?php endif; ?>
                                        <?php if($row->check_in_time): ?>
                                            · Check-in <?php echo e($row->check_in_time->format('h:i A')); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="badge bg-<?php echo e($attendanceStatusBadges[$row->status] ?? 'secondary'); ?>">
                                    <?php echo e($attendanceStatusLabels[$row->status] ?? ucfirst($row->status)); ?>

                                </span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php elseif($attendanceBatches > 0): ?>
                        <div class="trainee-empty py-3">
                            No sessions scheduled for today.
                            <?php if($overallAttendance !== null): ?>
                            <div class="small mt-1">Your overall attendance is <?php echo e(number_format($overallAttendance, 1)); ?>%.</div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="trainee-empty py-3">Attendance is not available for your trainings yet.</div>
                    <?php endif; ?>
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
                        <?php if($unreadNotificationsCount > 0): ?>
                        <span class="badge bg-danger badge-blink"><?php echo e($unreadNotificationsCount); ?> unread</span>
                        <?php endif; ?>
                        <a href="<?php echo e(route('notifications.index')); ?>" class="trainee-panel-link">View all</a>
                    </div>
                </div>
                <div class="trainee-panel-body">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('notifications.read', $notification)); ?>" class="trainee-notification-item text-decoration-none text-body d-block">
                        <div class="fw-semibold small"><?php echo e($notification->title); ?></div>
                        <div class="text-muted small"><?php echo e($notification->message); ?></div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            <i class="bi bi-clock me-1"></i><?php echo e($notification->created_at->diffForHumans()); ?>

                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="trainee-empty">No new notifications.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if($traineeProfile): ?>
        <div class="col-lg-5">
            <div class="trainee-panel">
                <div class="trainee-panel-header">
                    <h5><i class="bi bi-person-badge me-2 text-success"></i>Profile Summary</h5>
                    <a href="<?php echo e(route('trainee.profile.show')); ?>" class="trainee-panel-link">Full profile</a>
                </div>
                <div class="trainee-panel-body">
                    <div class="trainee-profile-card">
                        <div class="trainee-profile-row">
                            <span>Name</span>
                            <strong><?php echo e($traineeProfile->emp_name ?? $user->name); ?></strong>
                        </div>
                        <div class="trainee-profile-row">
                            <span>Organization</span>
                            <strong><?php echo e($traineeProfile->organization->name ?? 'N/A'); ?></strong>
                        </div>
                        <div class="trainee-profile-row">
                            <span>Designation</span>
                            <strong>
                                <?php echo e($traineeProfile->designation ?? 'N/A'); ?>

                                <?php if($traineeProfile->bps): ?> · BPS-<?php echo e($traineeProfile->bps); ?> <?php endif; ?>
                            </strong>
                        </div>
                        <div class="trainee-profile-row">
                            <span>District</span>
                            <strong><?php echo e($traineeProfile->district->name ?? 'N/A'); ?></strong>
                        </div>
                        <div class="trainee-profile-row">
                            <span>Contact</span>
                            <strong><?php echo e($traineeProfile->contact_no ?? 'N/A'); ?></strong>
                        </div>
                    </div>
                    <?php if (! ($user->profile_completed)): ?>
                    <a href="<?php echo e(route('trainee.profile.edit')); ?>" class="btn btn-success btn-sm w-100 mt-3">
                        <i class="bi bi-pencil me-1"></i>Complete Profile
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/trainee/dashboard.blade.php ENDPATH**/ ?>