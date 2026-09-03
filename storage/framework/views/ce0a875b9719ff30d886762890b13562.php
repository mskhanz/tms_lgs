

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use App\Models\TrainingProgram;

    $categoryLabels = TrainingProgram::categoryOptions();
    $maxCategoryCount = max($trainingsByCategory->max('count') ?? 0, 1);
    $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100) : 0;
    $enrolledCount = max(0, $totalEnrollments - $ongoingEnrollments - $completedEnrollments);

    $statusBadges = [
        'completed' => 'success',
        'in_progress' => 'primary',
        'enrolled' => 'warning text-dark',
        'dropped' => 'danger',
        'failed' => 'danger',
    ];
?>

<div class="admin-dashboard">
    <div class="dashboard-hero">
        <div>
            <p class="dashboard-kicker">Administration</p>
            <h1>Welcome back, <?php echo e($user->name); ?></h1>
            <p><?php echo e(config('app.name')); ?> · <?php echo e(now()->format('l, F j, Y')); ?></p>
        </div>
        <div class="dashboard-hero-actions">
            <a href="<?php echo e(route('admin.enrollments.create')); ?>" class="btn btn-light btn-sm">
                <i class="bi bi-person-plus me-1"></i>New Enrollment
            </a>
            <a href="<?php echo e(route('admin.programs.create')); ?>" class="btn btn-outline-light btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add Program
            </a>
            <a href="<?php echo e(route('admin.attendance.index')); ?>" class="btn btn-outline-light btn-sm">
                <i class="bi bi-calendar-check me-1"></i>Attendance
            </a>
        </div>
    </div>

    <?php if($pendingNominations > 0 || $pendingPrograms > 0): ?>
    <div class="dashboard-alert-pending d-flex align-items-start gap-3">
        <i class="bi bi-exclamation-triangle-fill text-warning fs-5 mt-1"></i>
        <div>
            <h6 class="fw-bold mb-1 text-dark">Pending approvals</h6>
            <ul class="mb-0 ps-3 small text-secondary">
                <?php if($pendingNominations > 0): ?>
                <li><?php echo e(number_format($pendingNominations)); ?> nomination(s) awaiting review</li>
                <?php endif; ?>
                <?php if($pendingPrograms > 0): ?>
                <li><?php echo e(number_format($pendingPrograms)); ?> training program(s) awaiting approval</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <div class="dashboard-kpi-grid">
        <a href="<?php echo e(route('admin.trainees.index')); ?>" class="dashboard-kpi-card">
            <div class="dashboard-kpi-icon emerald"><i class="bi bi-people"></i></div>
            <div>
                <span class="dashboard-kpi-value"><?php echo e(number_format($totalTrainees)); ?></span>
                <span class="dashboard-kpi-label">Total Trainees</span>
                <span class="dashboard-kpi-meta"><?php echo e(number_format($totalTrainers)); ?> trainers registered</span>
            </div>
        </a>
        <a href="<?php echo e(route('admin.enrollments.index')); ?>" class="dashboard-kpi-card">
            <div class="dashboard-kpi-icon blue"><i class="bi bi-person-check"></i></div>
            <div>
                <span class="dashboard-kpi-value"><?php echo e(number_format($totalEnrollments)); ?></span>
                <span class="dashboard-kpi-label">Total Enrollments</span>
                <span class="dashboard-kpi-meta"><?php echo e($completionRate); ?>% completion rate</span>
            </div>
        </a>
        <a href="<?php echo e(route('admin.programs.index')); ?>" class="dashboard-kpi-card">
            <div class="dashboard-kpi-icon violet"><i class="bi bi-journal-bookmark"></i></div>
            <div>
                <span class="dashboard-kpi-value"><?php echo e(number_format($activePrograms)); ?></span>
                <span class="dashboard-kpi-label">Active Programs</span>
                <span class="dashboard-kpi-meta"><?php echo e(number_format($totalPrograms)); ?> total · <?php echo e(number_format($completedPrograms)); ?> completed</span>
            </div>
        </a>
        <a href="<?php echo e(route('admin.batches.index')); ?>" class="dashboard-kpi-card">
            <div class="dashboard-kpi-icon amber"><i class="bi bi-collection"></i></div>
            <div>
                <span class="dashboard-kpi-value"><?php echo e(number_format($activeBatches)); ?></span>
                <span class="dashboard-kpi-label">Active Batches</span>
                <span class="dashboard-kpi-meta"><?php echo e(number_format($upcomingBatches)); ?> upcoming</span>
            </div>
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h5><i class="bi bi-pie-chart me-2 text-success"></i>Enrollment Overview</h5>
                </div>
                <div class="dashboard-panel-body">
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
                            <strong class="text-secondary"><?php echo e(number_format($totalCertificates)); ?></strong>
                            <span>Certificates</span>
                        </div>
                    </div>
                    <p class="dashboard-section-title mb-2">Completion progress</p>
                    <div class="dashboard-progress-item">
                        <div class="dashboard-progress-head">
                            <span>Completed enrollments</span>
                            <span><?php echo e($completionRate); ?>%</span>
                        </div>
                        <div class="dashboard-progress">
                            <div class="dashboard-progress-bar" style="width: <?php echo e($completionRate); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h5><i class="bi bi-tags me-2 text-success"></i>Programs by Category</h5>
                    <a href="<?php echo e(route('admin.programs.index')); ?>" class="panel-link">View all</a>
                </div>
                <div class="dashboard-panel-body">
                    <?php $__empty_1 = true; $__currentLoopData = $trainingsByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $label = $categoryLabels[$item->category] ?? ucwords(str_replace('_', ' ', (string) $item->category));
                        $width = round(($item->count / $maxCategoryCount) * 100);
                    ?>
                    <div class="dashboard-progress-item">
                        <div class="dashboard-progress-head">
                            <span><?php echo e($label); ?></span>
                            <span><?php echo e($item->count); ?></span>
                        </div>
                        <div class="dashboard-progress">
                            <div class="dashboard-progress-bar" style="width: <?php echo e($width); ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="dashboard-empty">No programs categorized yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h5><i class="bi bi-lightning-charge me-2 text-success"></i>Quick Actions</h5>
                </div>
                <div class="dashboard-panel-body">
                    <div class="dashboard-quick-links">
                        <a href="<?php echo e(route('admin.trainees.index')); ?>" class="dashboard-quick-link">
                            <i class="bi bi-people"></i><span>Trainees</span>
                        </a>
                        <a href="<?php echo e(route('admin.enrollments.index')); ?>" class="dashboard-quick-link">
                            <i class="bi bi-person-check"></i><span>Enrollments</span>
                        </a>
                        <a href="<?php echo e(route('admin.batches.index')); ?>" class="dashboard-quick-link">
                            <i class="bi bi-calendar-event"></i><span>Batches</span>
                        </a>
                        <a href="<?php echo e(route('admin.attendance.index')); ?>" class="dashboard-quick-link">
                            <i class="bi bi-calendar-check"></i><span>Attendance</span>
                        </a>
                        <a href="<?php echo e(route('admin.quizzes.index')); ?>" class="dashboard-quick-link">
                            <i class="bi bi-clipboard-check"></i><span>Quizzes</span>
                        </a>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="dashboard-quick-link">
                            <i class="bi bi-person-gear"></i><span>Users</span>
                        </a>
                    </div>

                    <?php if(! is_null($onlineUsersCount)): ?>
                    <hr class="my-3 text-muted">
                    <p class="dashboard-section-title">System activity</p>
                    <div class="dashboard-mini-stats">
                        <a href="<?php echo e(route('admin.online-users.index')); ?>" class="dashboard-mini-stat text-decoration-none">
                            <strong class="text-success"><span class="online-dot me-1"></span><?php echo e(number_format($onlineUsersCount)); ?></strong>
                            <span>Users online</span>
                        </a>
                        <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="dashboard-mini-stat text-decoration-none">
                            <strong><?php echo e(number_format($activitiesToday)); ?></strong>
                            <span>Activities today</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h5><i class="bi bi-clock-history me-2 text-success"></i>Recent Enrollments</h5>
                    <a href="<?php echo e(route('admin.enrollments.index')); ?>" class="panel-link">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table dashboard-table mb-0">
                        <thead>
                            <tr>
                                <th>Trainee</th>
                                <th>Program</th>
                                <th>Batch</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="fw-semibold">
                                    <?php if($enrollment->trainee): ?>
                                    <a href="<?php echo e(route('admin.trainees.show', $enrollment->trainee_id)); ?>" class="text-decoration-none text-dark">
                                        <?php echo e($enrollment->trainee->name); ?>

                                    </a>
                                    <?php else: ?>
                                    N/A
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($enrollment->trainingBatch->trainingProgram->title ?? 'N/A'); ?></td>
                                <td><?php echo e($enrollment->trainingBatch->batch_code ?? 'N/A'); ?></td>
                                <td><?php echo e($enrollment->enrollment_date?->format('d M Y') ?? '—'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($statusBadges[$enrollment->status] ?? 'secondary'); ?>">
                                        <?php echo e(ucwords(str_replace('_', ' ', $enrollment->status))); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="dashboard-empty">No enrollments recorded yet.</div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <h5><i class="bi bi-calendar-week me-2 text-success"></i>Upcoming Batches</h5>
                    <a href="<?php echo e(route('admin.batches.index')); ?>" class="panel-link">View all</a>
                </div>
                <div class="dashboard-panel-body">
                    <?php $__empty_1 = true; $__currentLoopData = $upcomingBatchesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="dashboard-batch-item">
                        <div>
                            <div class="small text-muted mb-1"><?php echo e($batch->trainingProgram->title ?? 'N/A'); ?></div>
                            <a href="<?php echo e(route('admin.batches.show', $batch)); ?>" class="dashboard-batch-code"><?php echo e($batch->batch_code); ?></a>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-calendar3 me-1"></i><?php echo e($batch->start_date->format('d M Y')); ?>

                                <?php if($batch->venue): ?>
                                · <i class="bi bi-geo-alt me-1"></i><?php echo e($batch->venue); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-info"><?php echo e($batch->seats_filled); ?>/<?php echo e($batch->total_seats); ?></span>
                            <div class="small text-muted mt-1">seats filled</div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="dashboard-empty">No upcoming batches scheduled.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if($enrollmentsByOrganization->count() > 0): ?>
    <div class="dashboard-panel">
        <div class="dashboard-panel-header">
            <h5><i class="bi bi-building me-2 text-success"></i>Top Organizations by Trainees</h5>
        </div>
        <div class="dashboard-panel-body">
            <div class="row g-3">
                <?php $__currentLoopData = $enrollmentsByOrganization->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $maxOrg = max($enrollmentsByOrganization->max('trainee_profiles_count') ?? 1, 1);
                    $orgWidth = round(($organization->trainee_profiles_count / $maxOrg) * 100);
                ?>
                <div class="col-md-6">
                    <div class="dashboard-progress-item">
                        <div class="dashboard-progress-head">
                            <span><?php echo e($organization->name); ?></span>
                            <span><?php echo e($organization->trainee_profiles_count); ?></span>
                        </div>
                        <div class="dashboard-progress">
                            <div class="dashboard-progress-bar" style="width: <?php echo e($orgWidth); ?>%"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>