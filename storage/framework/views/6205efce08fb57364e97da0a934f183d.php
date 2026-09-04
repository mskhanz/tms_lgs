<?php $__env->startSection('title', 'My Enrollments'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statusBadges = [
        'completed' => 'success',
        'in_progress' => 'primary',
        'enrolled' => 'primary',
        'dropped' => 'danger',
        'failed' => 'danger',
    ];
    $statusLabels = [
        'completed' => 'Completed',
        'in_progress' => 'Ongoing',
        'enrolled' => 'Ongoing',
        'dropped' => 'Dropped',
        'failed' => 'Failed',
    ];
    $pageTitle = match ($status) {
        'ongoing' => 'Ongoing Trainings',
        'completed' => 'Completed Trainings',
        'enrolled' => 'Enrolled Trainings',
        default => 'My Enrollments',
    };
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-journal-text me-2"></i><?php echo e($pageTitle); ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('trainee.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Enrollments</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('trainee.dashboard')); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Dashboard
        </a>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="<?php echo e(route('trainee.enrollments.index')); ?>"
       class="btn btn-sm <?php echo e(! $status ? 'btn-success' : 'btn-outline-secondary'); ?>">
        All (<?php echo e($counts['all']); ?>)
    </a>
    <a href="<?php echo e(route('trainee.enrollments.index', ['status' => 'ongoing'])); ?>"
       class="btn btn-sm <?php echo e($status === 'ongoing' ? 'btn-primary' : 'btn-outline-primary'); ?>">
        Ongoing (<?php echo e($counts['ongoing']); ?>)
    </a>
    <a href="<?php echo e(route('trainee.enrollments.index', ['status' => 'enrolled'])); ?>"
       class="btn btn-sm <?php echo e($status === 'enrolled' ? 'btn-warning' : 'btn-outline-warning'); ?>">
        Enrolled (<?php echo e($counts['enrolled']); ?>)
    </a>
    <a href="<?php echo e(route('trainee.enrollments.index', ['status' => 'completed'])); ?>"
       class="btn btn-sm <?php echo e($status === 'completed' ? 'btn-success' : 'btn-outline-success'); ?>">
        Completed (<?php echo e($counts['completed']); ?>)
    </a>
</div>

<?php if($enrollments->isEmpty()): ?>
<div class="alert alert-info mb-0">
    <i class="bi bi-info-circle me-2"></i>
    <?php if($status === 'ongoing'): ?>
        No ongoing trainings at the moment.
    <?php else: ?>
        No enrollments found for this filter.
    <?php endif; ?>
</div>
<?php else: ?>
<div class="row g-3">
    <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $batch = $enrollment->trainingBatch;
        $program = $batch?->trainingProgram;
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <h5 class="card-title h6 mb-0"><?php echo e($program->title ?? 'Training'); ?></h5>
                    <span class="badge bg-<?php echo e($statusBadges[$enrollment->status] ?? 'secondary'); ?>">
                        <?php echo e($statusLabels[$enrollment->status] ?? ucfirst(str_replace('_', ' ', $enrollment->status))); ?>

                    </span>
                </div>
                <ul class="list-unstyled small text-muted mb-3 flex-grow-1">
                    <li><i class="bi bi-hash me-1"></i><?php echo e($batch->batch_code ?? 'N/A'); ?></li>
                    <?php if($batch?->start_date || $batch?->end_date): ?>
                    <li>
                        <i class="bi bi-calendar-range me-1"></i>
                        <?php echo e($batch->start_date?->format('d M Y') ?? '—'); ?>

                        –
                        <?php echo e($batch->end_date?->format('d M Y') ?? '—'); ?>

                    </li>
                    <?php endif; ?>
                    <?php if($enrollment->enrollment_date): ?>
                    <li><i class="bi bi-calendar-check me-1"></i>Enrolled: <?php echo e($enrollment->enrollment_date->format('d M Y')); ?></li>
                    <?php endif; ?>
                    <?php if($program?->conductingOrganization): ?>
                    <li><i class="bi bi-building me-1"></i><?php echo e($program->conductingOrganization->name); ?></li>
                    <?php endif; ?>
                    <?php if($enrollment->attendance_percentage !== null): ?>
                    <li><i class="bi bi-person-check me-1"></i>Attendance: <?php echo e(number_format((float) $enrollment->attendance_percentage, 1)); ?>%</li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('trainee.attendance.show', $enrollment)); ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-calendar-check me-1"></i>Attendance
                    </a>
                    <a href="<?php echo e(route('trainee.dashboard')); ?>" class="btn btn-sm btn-outline-secondary">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/trainee/enrollments/index.blade.php ENDPATH**/ ?>