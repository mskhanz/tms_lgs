<?php $__env->startSection('title', $assignment->title); ?>

<?php $__env->startPush('styles'); ?>
<?php echo $__env->make('assignments._workspace-styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $canEdit = $assignment->isAvailable() && (! $submission?->isSubmitted() || $assignment->canTraineeEditSubmission());
    if ($submission?->isSubmitted()) {
        $statusLabel = $submission->statusLabel();
        $statusClass = $submission->isLate() ? 'bg-warning text-dark' : 'bg-success text-white';
    } elseif ($submission) {
        $statusLabel = 'Draft';
        $statusClass = 'bg-info text-white';
    } else {
        $statusLabel = $assignment->traineeStatusLabel();
        $statusClass = $assignment->traineeStatus() === 'open' ? 'bg-secondary text-white' : 'bg-warning text-dark';
    }
?>

<div class="asg-workspace">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1"><?php echo e($assignment->title); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('trainee.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('trainee.assignments.index')); ?>">Assignments</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
            <a href="<?php echo e(route('trainee.assignments.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>All assignments
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="asg-stats">
        <div class="asg-stat">
            <b><span class="badge <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span></b>
            <span>Status</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($submission?->submitted_at?->format('d M Y, h:i A') ?? '—'); ?></b>
            <span>Submitted</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($assignment->attachments->count()); ?></b>
            <span>Materials</span>
        </div>
        <div class="asg-stat">
            <b><?php echo e($submission?->marks !== null ? $submission->marks.' / '.$assignment->total_marks : '—'); ?></b>
            <span>Score</span>
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
                        <div class="asg-file-actions">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-asg-preview
                                    data-asg-name="<?php echo e($file->displayName()); ?>"
                                    data-asg-kind="<?php echo e($file->previewKind()); ?>"
                                    data-asg-view="<?php echo e(route('trainee.assignments.attachments.view', [$assignment, $file])); ?>"
                                    data-asg-download="<?php echo e(route('trainee.assignments.attachments.download', [$assignment, $file])); ?>">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <a href="<?php echo e(route('trainee.assignments.attachments.download', [$assignment, $file])); ?>" class="btn btn-sm btn-outline-success">
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
            <h2>Your submission</h2>
            <?php if($submission?->submitted_at): ?>
            <span class="badge bg-light text-dark"><?php echo e($submission->submitted_at->format('d M Y, h:i A')); ?></span>
            <?php endif; ?>
        </div>
        <div class="asg-panel-body">
            <?php if($submission?->marks !== null || $submission?->admin_feedback): ?>
            <div class="asg-feedback">
                <div class="asg-feedback-title">Result from admin</div>
                <?php if($submission->marks !== null): ?>
                <div class="fw-semibold mb-1">Score: <?php echo e($submission->marks); ?> / <?php echo e(number_format((float) $assignment->total_marks, 0)); ?></div>
                <?php endif; ?>
                <?php if($submission->admin_feedback): ?>
                <div class="asg-prose"><?php echo e($submission->admin_feedback); ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if($canEdit): ?>
            <form method="POST" action="<?php echo e(route('trainee.assignments.submit', $assignment)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Written response</label>
                    <textarea name="written_response" id="written_response" class="form-control asg-richtext" rows="10"
                              placeholder="Write your answer or notes here..."><?php echo e(old('written_response', $submission->written_response ?? '')); ?></textarea>
                    <div class="form-text">You can use bold, underline, colors, lists, and more.</div>
                </div>

                <?php if($submission && $submission->files->count()): ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Uploaded files</label>
                    <?php $__currentLoopData = $submission->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    data-asg-view="<?php echo e(route('trainee.assignments.files.view', [$assignment, $file])); ?>"
                                    data-asg-download="<?php echo e(route('trainee.assignments.files.download', [$assignment, $file])); ?>">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <a href="<?php echo e(route('trainee.assignments.files.download', [$assignment, $file])); ?>" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i>
                            </a>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="remove_files[]" value="<?php echo e($file->id); ?>" id="rmf_<?php echo e($file->id); ?>">
                                <label class="form-check-label text-danger small" for="rmf_<?php echo e($file->id); ?>">Remove</label>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload documents</label>
                    <input type="file" name="files[]" class="form-control" multiple
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                    <div class="form-text">Word, PDF, or images. Max 10 MB each.</div>
                    <?php $__errorArgs = ['files.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="asg-actions">
                    <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">
                        <i class="bi bi-save me-1"></i>Save draft
                    </button>
                    <button type="submit" name="action" value="submit" class="btn btn-success"
                            onclick="return confirm('Submit this assignment now?')">
                        <i class="bi bi-check-circle me-1"></i>Submit assignment
                    </button>
                </div>
            </form>
            <?php else: ?>
                <?php if($submission): ?>
                <div class="asg-prose mb-3"><?php echo \App\Support\HtmlContent::display($submission->written_response, 'No written response.'); ?></div>
                <?php if($submission->files->count()): ?>
                <div class="mb-3">
                    <?php $__currentLoopData = $submission->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="asg-file">
                        <div class="d-flex align-items-center gap-2" style="min-width:0;">
                            <div class="asg-file-icon"><i class="bi bi-file-earmark"></i></div>
                            <div class="asg-file-name"><?php echo e($file->original_name); ?></div>
                        </div>
                        <div class="asg-file-actions">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-asg-preview
                                    data-asg-name="<?php echo e($file->original_name); ?>"
                                    data-asg-kind="<?php echo e($file->previewKind()); ?>"
                                    data-asg-view="<?php echo e(route('trainee.assignments.files.view', [$assignment, $file])); ?>"
                                    data-asg-download="<?php echo e(route('trainee.assignments.files.download', [$assignment, $file])); ?>">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <a href="<?php echo e(route('trainee.assignments.files.download', [$assignment, $file])); ?>" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
                <p class="text-muted small mb-0">
                    <?php if($submission->submitted_at): ?>
                        Submitted <?php echo e($submission->submitted_at->format('d M Y, h:i A')); ?>.
                    <?php endif; ?>
                    <?php if (! ($assignment->canTraineeEditSubmission())): ?>
                        Editing is closed after the due date.
                    <?php endif; ?>
                </p>
                <?php else: ?>
                <p class="asg-empty">This assignment is not open for submission.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php echo $__env->make('assignments._file-preview-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('assignments._richtext', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('assignments._due-countdown-script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/trainee/assignments/show.blade.php ENDPATH**/ ?>