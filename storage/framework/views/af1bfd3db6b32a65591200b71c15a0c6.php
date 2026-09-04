<?php $__env->startSection('title', 'My Quizzes'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $quizzes = $quizzes ?? collect();
    $attempts = $attempts ?? collect();
?>

<div class="page-header">
    <h1><i class="bi bi-clipboard-check me-2"></i>My Quizzes</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('trainee.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Quizzes</li>
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
    <strong>Quizzes could not be loaded.</strong>
    <div class="small mt-1"><?php echo e($loadError); ?></div>
</div>
<?php endif; ?>

<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-6 col-lg-4">
        <?php echo $__env->make('trainee.quizzes._card', ['quiz' => $quiz, 'attempts' => $attempts], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle me-2"></i>
            No quizzes assigned to your enrollments at the moment.
            <?php if(\App\Models\Quiz::where('is_active', true)->exists()): ?>
            <div class="small mt-2 mb-0">
                Quizzes appear here only when you are enrolled in the assigned training program or batch.
                Contact your training officer if you believe this is incorrect.
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/trainee/quizzes/index.blade.php ENDPATH**/ ?>