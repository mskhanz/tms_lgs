<?php $__env->startSection('title', 'My Assignments'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .asg-countdown {
        display: inline-block;
        margin-left: 0.35rem;
        color: #dc2626;
        font-weight: 700;
        white-space: nowrap;
    }
    .asg-countdown.asg-countdown-overdue { color: #991b1b; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $assignments = $assignments ?? collect();
    $submissions = $submissions ?? collect();
?>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2"></i>My Assignments</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('trainee.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Assignments</li>
        </ol>
    </nav>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<?php if(!empty($loadError)): ?>
<div class="alert alert-danger">
    <i class="bi bi-exclamation-octagon me-2"></i>
    <strong>Assignments could not be loaded.</strong>
    <div class="small mt-1"><?php echo e($loadError); ?></div>
</div>
<?php endif; ?>

<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-6 col-lg-4">
        <?php echo $__env->make('trainee.assignments._card', ['assignment' => $assignment, 'submissions' => $submissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle me-2"></i>
            No assignments assigned to your enrollments at the moment.
        </div>
    </div>
    <?php endif; ?>
</div>

<?php echo $__env->make('assignments._due-countdown-script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/trainee/assignments/index.blade.php ENDPATH**/ ?>