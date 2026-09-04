<?php $__env->startSection('title', $assignment->title); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2"></i><?php echo e($assignment->title); ?></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.assignments.index')); ?>">Assignments</a></li>
            <li class="breadcrumb-item active">Manage</li>
        </ol>
    </nav>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-primary"><?php echo e($assignedCount); ?></h3><small class="text-muted">Assigned trainees</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-success"><?php echo e($submittedCount); ?></h3><small class="text-muted">Submitted</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-info"><?php echo e(number_format((float) $assignment->total_marks, 0)); ?></h3><small class="text-muted">Total marks</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-warning"><?php echo e($assignment->due_at?->format('d M') ?? '∞'); ?></h3><small class="text-muted">Due date</small></div></div></div>
</div>

<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="<?php echo e(route('admin.assignments.edit', $assignment)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
    <form action="<?php echo e(route('admin.assignments.toggle-status', $assignment)); ?>" method="POST"><?php echo csrf_field(); ?>
        <button class="btn btn-outline-<?php echo e($assignment->is_active ? 'warning' : 'success'); ?>">
            <?php echo e($assignment->is_active ? 'Deactivate' : 'Activate & Notify'); ?>

        </button>
    </form>
    <form action="<?php echo e(route('admin.assignments.destroy', $assignment)); ?>" method="POST" onsubmit="return confirm('Delete this assignment?')">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Delete</button>
    </form>
</div>

<div class="alert <?php echo e($assignment->assign_to ? 'alert-info' : 'alert-warning'); ?>">
    <strong>Assigned to:</strong> <?php echo e($assignment->assignmentLabel()); ?>

</div>

<div class="row g-4 mb-4">
    <div class="col-lg-9">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Instructions / Detail</h5></div>
            <div class="card-body" style="white-space: pre-wrap;"><?php echo e($assignment->instructions ?: 'No instructions.'); ?></div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Materials</h5></div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $assignment->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border rounded p-2 mb-2">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="small" style="min-width: 0;">
                            <strong class="d-block text-break"><?php echo e($file->displayName()); ?></strong>
                            <?php if($file->title && $file->title !== $file->original_name): ?>
                            <span class="text-muted text-break"><?php echo e($file->original_name); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('admin.assignments.attachments.download', [$assignment, $file])); ?>" class="btn btn-sm btn-outline-primary flex-shrink-0">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted mb-0">No files attached.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Submissions</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 56px;">S. No</th>
                                <th style="width: 64px;">Photo</th>
                                <th>Trainee</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Marks</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $assignment->submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $photoUrl = $submission->user?->photoUrl();
                                $initials = strtoupper(substr($submission->user->traineeProfile->emp_name ?? $submission->user->name ?? 'T', 0, 1));
                            ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td>
                                    <?php if($photoUrl): ?>
                                    <img src="<?php echo e($photoUrl); ?>" alt="Photo" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                    <?php else: ?>
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-muted fw-semibold" style="width:40px;height:40px;"><?php echo e($initials); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($submission->user->traineeProfile->emp_name ?? $submission->user->name); ?></strong>
                                    <div class="small text-muted"><?php echo e($submission->user->traineeProfile->cnic_no ?? ''); ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($submission->isSubmitted() ? ($submission->isLate() ? 'warning text-dark' : 'success') : 'secondary'); ?>">
                                        <?php echo e($submission->statusLabel()); ?>

                                    </span>
                                </td>
                                <td><?php echo e($submission->submitted_at?->format('d M Y H:i') ?? '—'); ?></td>
                                <td><?php echo e($submission->marks !== null ? $submission->marks : '—'); ?></td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('admin.assignments.submissions.show', [$assignment, $submission])); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">No submissions yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/assignments/show.blade.php ENDPATH**/ ?>