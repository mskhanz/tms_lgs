<?php $__env->startSection('title', 'Submission - '.$assignment->title); ?>

<?php $__env->startPush('styles'); ?>
<?php echo $__env->make('assignments._workspace-styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $trainee = $submission->user;
    $profile = $trainee->traineeProfile;
    $traineeName = $profile->emp_name ?? $trainee->name;
    $photoUrl = $trainee->photoUrl();
    $initials = strtoupper(substr($traineeName ?? 'T', 0, 1));
    $statusClass = $submission->isSubmitted()
        ? ($submission->isLate() ? 'bg-warning text-dark' : 'bg-success text-white')
        : 'bg-secondary text-white';
?>

<div class="asg-workspace">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1">Submission review</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.assignments.index')); ?>">Assignments</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.assignments.show', $assignment)); ?>"><?php echo e(Str::limit($assignment->title, 32)); ?></a></li>
                        <li class="breadcrumb-item active">Submission</li>
                    </ol>
                </nav>
            </div>
            <a href="<?php echo e(route('admin.assignments.show', $assignment)); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to assignment
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="asg-stats">
        <div class="asg-stat">
            <b><span class="badge <?php echo e($statusClass); ?>"><?php echo e($submission->statusLabel()); ?></span></b>
            <span>Status</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($submission->submitted_at?->format('d M Y, h:i A') ?? '—'); ?></b>
            <span>Submitted</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($submission->files->count()); ?></b>
            <span>Files</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($submission->marks !== null ? $submission->marks.' / '.$assignment->total_marks : '—'); ?></b>
            <span>Score</span>
        </div>
    </div>

    <div class="row g-3 mb-3 align-items-start">
        <div class="col-lg-4">
            <div class="asg-panel asg-panel-auto">
                <div class="asg-panel-head"><h2>Trainee</h2></div>
                <div class="asg-panel-body">
                    <div class="asg-trainee-row">
                        <div class="asg-trainee-photo">
                            <?php if($photoUrl): ?>
                                <img src="<?php echo e($photoUrl); ?>" alt="<?php echo e($traineeName); ?>" class="asg-avatar">
                            <?php else: ?>
                                <div class="asg-avatar asg-avatar-fallback"><?php echo e($initials); ?></div>
                            <?php endif; ?>
                        </div>
                        <ul class="asg-meta-list asg-meta-list-left">
                            <li><span class="label">Name</span><span class="value"><?php echo e($traineeName); ?></span></li>
                            <li><span class="label">Contact no</span><span class="value"><?php echo e($profile->contact_no ?? '—'); ?></span></li>
                            <li><span class="label">Email</span><span class="value"><?php echo e($trainee->email); ?></span></li>
                            <li><span class="label">Organization</span><span class="value"><?php echo e($profile?->organization?->name ?? '—'); ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
                                <span class="value"><?php echo e($assignment->due_at?->format('d M Y, h:i A') ?? 'No due date'); ?></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="asg-panel asg-panel-auto mb-3">
        <div class="asg-panel-head"><h2>Written response</h2></div>
        <div class="asg-panel-body">
            <div class="asg-prose"><?php echo e($submission->written_response ?: 'No written response provided.'); ?></div>
        </div>
    </div>

    <div class="asg-panel asg-panel-auto mb-3">
        <div class="asg-panel-head"><h2>Uploaded documents</h2></div>
        <div class="asg-panel-body">
            <?php $__empty_1 = true; $__currentLoopData = $submission->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="asg-file">
                <div class="d-flex align-items-center gap-2" style="min-width:0;">
                    <div class="asg-file-icon"><i class="bi bi-file-earmark"></i></div>
                    <div style="min-width:0;">
                        <div class="asg-file-name"><?php echo e($file->original_name); ?></div>
                    </div>
                </div>
                <div class="asg-file-actions">
                    <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-asg-preview
                            data-asg-name="<?php echo e($file->original_name); ?>"
                            data-asg-kind="<?php echo e($file->previewKind()); ?>"
                            data-asg-view="<?php echo e(route('admin.assignments.files.view', [$assignment, $file])); ?>"
                            data-asg-download="<?php echo e(route('admin.assignments.files.download', [$assignment, $file])); ?>">
                        <i class="bi bi-eye"></i> View
                    </button>
                    <a href="<?php echo e(route('admin.assignments.files.download', [$assignment, $file])); ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="asg-empty">No documents uploaded.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="asg-panel asg-panel-auto">
        <div class="asg-panel-head"><h2>Marks &amp; feedback</h2></div>
        <div class="asg-panel-body">
            <form method="POST" action="<?php echo e(route('admin.assignments.submissions.feedback', [$assignment, $submission])); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Marks (out of <?php echo e(number_format((float) $assignment->total_marks, 0)); ?>)</label>
                    <input type="number" step="0.01" name="marks" class="form-control"
                           value="<?php echo e(old('marks', $submission->marks)); ?>"
                           min="0" max="<?php echo e((float) $assignment->total_marks); ?>"
                           placeholder="Enter marks">
                </div>
                <div class="mb-3">
                    <label class="form-label">Feedback</label>
                    <textarea name="admin_feedback" class="form-control" rows="5"
                              placeholder="Write feedback for the trainee..."><?php echo e(old('admin_feedback', $submission->admin_feedback)); ?></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i>Save feedback
                </button>
            </form>
        </div>
    </div>
</div>

<?php echo $__env->make('assignments._file-preview-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/assignments/submission.blade.php ENDPATH**/ ?>