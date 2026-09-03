<?php $__env->startSection('title', 'Edit Quiz'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit Quiz</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.quizzes.index')); ?>">Quizzes</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <?php echo $__env->make('admin.quizzes._form', ['quiz' => $quiz], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/edit.blade.php ENDPATH**/ ?>