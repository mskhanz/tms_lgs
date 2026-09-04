<?php $__env->startSection('title', 'Assignments'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2"></i>Assignments</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Assignments</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?php echo e(route('admin.assignments.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Create Assignment
    </a>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search assignments..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Assigned to</th>
                        <th>Marks</th>
                        <th>Files</th>
                        <th>Due</th>
                        <th>Submissions</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($assignment->title); ?></strong>
                            <?php if($assignment->instructions): ?>
                            <br><small class="text-muted"><?php echo e(Str::limit(strip_tags($assignment->instructions), 60)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($assignment->assign_to): ?>
                                <span class="badge bg-<?php echo e($assignment->assign_to === 'batch' ? 'primary' : 'info'); ?>"><?php echo e($assignment->assign_to === 'batch' ? 'Batch' : 'Program'); ?></span>
                                <div class="small text-muted"><?php echo e($assignment->assignmentLabel()); ?></div>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Not assigned</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e(number_format((float) $assignment->total_marks, 0)); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e($assignment->attachments_count); ?></span></td>
                        <td><?php echo e($assignment->due_at?->format('d M Y') ?? '—'); ?></td>
                        <td><?php echo e($assignment->submissions_count); ?></td>
                        <td>
                            <?php if($assignment->is_active): ?>
                            <span class="badge bg-success">Active</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="<?php echo e(route('admin.assignments.show', $assignment)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="<?php echo e(route('admin.assignments.edit', $assignment)); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No assignments yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($assignments->hasPages()): ?>
    <div class="card-footer"><?php echo e($assignments->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/assignments/index.blade.php ENDPATH**/ ?>