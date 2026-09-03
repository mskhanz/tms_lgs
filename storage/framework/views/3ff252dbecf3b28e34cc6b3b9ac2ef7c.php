<?php $__env->startSection('title', 'Registration Trainings'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="bi bi-mortarboard me-2"></i>Registration Training Options</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Registration Trainings</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-between mb-4">
    <p class="text-muted mb-0">These options appear on the trainee registration page.</p>
    <a href="<?php echo e(route('admin.registration-trainings.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Add Training Option
    </a>
</div>

<?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
<?php if(session('error')): ?><div class="alert alert-danger"><?php echo e(session('error')); ?></div><?php endif; ?>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Training Title</th>
                    <th>Trainees</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($training->sort_order); ?></td>
                    <td>
                        <strong><?php echo e($training->title); ?></strong>
                        <?php if($training->description): ?><br><small class="text-muted"><?php echo e(Str::limit($training->description, 80)); ?></small><?php endif; ?>
                    </td>
                    <td><span class="badge bg-info"><?php echo e($training->trainees_count); ?></span></td>
                    <td><span class="badge bg-<?php echo e($training->is_active ? 'success' : 'secondary'); ?>"><?php echo e($training->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('admin.registration-trainings.edit', $training)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="<?php echo e(route('admin.registration-trainings.destroy', $training)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this training option?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">No registration training options yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo $__env->make('admin.partials.pagination', ['paginator' => $trainings, 'label' => 'training options'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/registration-trainings/index.blade.php ENDPATH**/ ?>