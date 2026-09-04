<?php $__env->startSection('title', $assignment->title); ?>

<?php $__env->startPush('styles'); ?>
<?php echo $__env->make('assignments._workspace-styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statusClass = $assignment->is_active ? 'bg-success text-white' : 'bg-secondary text-white';
    $statusLabel = $assignment->is_active ? 'Active' : 'Inactive';
?>

<div class="asg-workspace">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1"><?php echo e($assignment->title); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.assignments.index')); ?>">Assignments</a></li>
                        <li class="breadcrumb-item active">Manage</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo e(route('admin.assignments.index')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>All assignments
                </a>
                <a href="<?php echo e(route('admin.assignments.edit', $assignment)); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form action="<?php echo e(route('admin.assignments.toggle-status', $assignment)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-sm btn-outline-<?php echo e($assignment->is_active ? 'warning' : 'success'); ?>">
                        <?php echo e($assignment->is_active ? 'Deactivate' : 'Activate & Notify'); ?>

                    </button>
                </form>
                <form action="<?php echo e(route('admin.assignments.destroy', $assignment)); ?>" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this assignment?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Delete</button>
                </form>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="asg-stats">
        <div class="asg-stat">
            <b><span class="badge <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span></b>
            <span>Status</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($assignedCount); ?></b>
            <span>Assigned</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($submittedCount); ?></b>
            <span>Submitted</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($assignment->attachments->count()); ?></b>
            <span>Materials</span>
        </div>
    </div>

    <div class="row g-3 mb-3 align-items-start">
        <div class="col-lg-8">
            <div class="asg-panel asg-panel-auto">
                <div class="asg-panel-head"><h2>Assignment</h2></div>
                <div class="asg-panel-body">
                    <ul class="asg-meta-list asg-meta-list-left">
                        <li><span class="label">Title</span><span class="value"><?php echo e($assignment->title); ?></span></li>
                        <li><span class="label">Assigned to</span><span class="value"><?php echo e($assignment->assignmentLabel()); ?></span></li>
                        <li class="asg-meta-pair">
                            <div>
                                <span class="label">Total marks</span>
                                <span class="value"><?php echo e(number_format((float) $assignment->total_marks, 0)); ?></span>
                            </div>
                            <div>
                                <span class="label">Due date</span>
                                <?php echo $__env->make('assignments._due-countdown', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="asg-panel asg-panel-auto">
                <div class="asg-panel-head"><h2>Materials</h2></div>
                <div class="asg-panel-body">
                    <?php $__empty_1 = true; $__currentLoopData = $assignment->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="asg-file">
                        <div class="d-flex align-items-center gap-2" style="min-width:0;">
                            <div class="asg-file-icon"><i class="bi bi-paperclip"></i></div>
                            <div style="min-width:0;">
                                <div class="asg-file-name"><?php echo e($file->displayName()); ?></div>
                                <?php if($file->title && $file->title !== $file->original_name): ?>
                                <div class="asg-file-sub"><?php echo e($file->original_name); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="asg-file-actions asg-file-actions-stack">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    title="View"
                                    data-asg-preview
                                    data-asg-name="<?php echo e($file->displayName()); ?>"
                                    data-asg-kind="<?php echo e($file->previewKind()); ?>"
                                    data-asg-view="<?php echo e(route('admin.assignments.attachments.view', [$assignment, $file])); ?>"
                                    data-asg-download="<?php echo e(route('admin.assignments.attachments.download', [$assignment, $file])); ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="<?php echo e(route('admin.assignments.attachments.download', [$assignment, $file])); ?>" class="btn btn-sm btn-outline-success" title="Download">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="asg-empty">No materials attached.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="asg-panel asg-panel-auto mb-3">
        <div class="asg-panel-head"><h2>Instructions / Detail</h2></div>
        <div class="asg-panel-body">
            <div class="asg-prose"><?php echo \App\Support\HtmlContent::display($assignment->instructions, 'No instructions provided.'); ?></div>
        </div>
    </div>

    <div class="asg-panel asg-panel-auto">
        <div class="asg-panel-head">
            <h2>Submissions</h2>
            <span class="badge bg-light text-dark"><?php echo e($assignment->submissions->count()); ?></span>
        </div>
        <div class="asg-panel-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle asg-table">
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
                            $traineeName = $submission->user->traineeProfile->emp_name ?? $submission->user->name ?? 'Trainee';
                            $initials = strtoupper(substr($traineeName, 0, 1));
                            $contact = $submission->user->traineeProfile->contact_no ?? '—';
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
                                <strong><?php echo e($traineeName); ?></strong>
                                <div class="small text-muted"><?php echo e($contact); ?></div>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($submission->isSubmitted() ? ($submission->isLate() ? 'warning text-dark' : 'success text-white') : 'secondary text-white'); ?>">
                                    <?php echo e($submission->statusLabel()); ?>

                                </span>
                            </td>
                            <td><?php echo e($submission->submitted_at?->format('d M Y, h:i A') ?? '—'); ?></td>
                            <td><?php echo e($submission->marks !== null ? $submission->marks.' / '.$assignment->total_marks : '—'); ?></td>
                            <td class="text-end">
                                <a href="<?php echo e(route('admin.assignments.submissions.show', [$assignment, $submission])); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No submissions yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('assignments._file-preview-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('assignments._due-countdown-script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/assignments/show.blade.php ENDPATH**/ ?>