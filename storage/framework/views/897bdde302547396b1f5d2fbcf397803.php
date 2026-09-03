<?php $__env->startSection('title', $quiz->title); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="bi bi-clipboard-check me-2"></i><?php echo e($quiz->title); ?></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.quizzes.index')); ?>">Quizzes</a></li>
            <li class="breadcrumb-item active">Manage</li>
        </ol>
    </nav>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-primary"><?php echo e($quiz->questions->count()); ?></h3><small class="text-muted">Questions</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-success"><?php echo e($quiz->totalMarks()); ?></h3><small class="text-muted">Total Marks</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-info"><?php echo e($quiz->duration_minutes ?? '∞'); ?></h3><small class="text-muted">Minutes</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-warning"><?php echo e($quiz->passing_percentage); ?>%</h3><small class="text-muted">Passing Score</small></div></div></div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="<?php echo e(route('admin.quizzes.edit', $quiz)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit Settings</a>
    <form action="<?php echo e(route('admin.quizzes.toggle-status', $quiz)); ?>" method="POST"><?php echo csrf_field(); ?>
        <button class="btn btn-outline-<?php echo e($quiz->is_active ? 'warning' : 'success'); ?>">
            <?php echo e($quiz->is_active ? 'Deactivate' : 'Activate'); ?>

        </button>
    </form>
</div>

<div class="alert <?php echo e($quiz->assign_to ? 'alert-info' : 'alert-warning'); ?>">
    <strong>Assigned to:</strong>
    <?php echo e($quiz->assignmentLabel()); ?>

    <?php if(! $quiz->assign_to): ?>
        <div class="small mt-1">Assign this quiz to a program or batch so enrolled trainees can take it.</div>
    <?php endif; ?>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Questions (<?php echo e($quiz->questions->count()); ?>)</h5>
                <span class="badge bg-success">Shuffle: <?php echo e($quiz->shuffle_questions ? 'ON' : 'OFF'); ?> / Options: <?php echo e($quiz->shuffle_options ? 'ON' : 'OFF'); ?></span>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $quiz->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php if($question->part): ?><span class="badge bg-light text-dark mb-2"><?php echo e($question->part); ?></span><?php endif; ?>
                            <p class="mb-2"><strong>Q<?php echo e($index + 1); ?>.</strong> <?php echo e($question->question_text); ?></p>
                            <ul class="list-unstyled ms-3 mb-0">
                                <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="<?php echo e($opt->is_correct ? 'text-success fw-bold' : ''); ?>">
                                    <?php echo e($opt->is_correct ? '✓' : '○'); ?> <?php echo e($opt->option_text); ?>

                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <form action="<?php echo e(route('admin.quizzes.questions.destroy', [$quiz, $question])); ?>" method="POST" onsubmit="return confirm('Delete this question?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center py-3">No questions yet. Add one below.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Add Question</h5></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.quizzes.questions.store', $quiz)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Part / Section</label>
                        <input type="text" name="part" class="form-control" placeholder="e.g. Part-I">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Question *</label>
                        <textarea name="question_text" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Marks</label>
                        <input type="number" name="marks" class="form-control" value="1" min="1">
                    </div>
                    <?php for($i = 0; $i < 4; $i++): ?>
                    <div class="mb-2">
                        <label class="form-label">Option <?php echo e(chr(65 + $i)); ?></label>
                        <input type="text" name="options[]" class="form-control" <?php echo e($i < 2 ? 'required' : ''); ?>>
                    </div>
                    <?php endfor; ?>
                    <div class="mb-3">
                        <label class="form-label">Correct Option *</label>
                        <select name="correct_option" class="form-select" required>
                            <option value="0">A</option>
                            <option value="1">B</option>
                            <option value="2">C</option>
                            <option value="3">D</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Question</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if($attempts->count()): ?>
<div class="card mt-4">
    <div class="card-header"><h5 class="mb-0">Trainee Attempts</h5></div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead><tr><th>Trainee</th><th>Score</th><th>%</th><th>Result</th><th>Submitted</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $attempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($attempt->user->name); ?></td>
                    <td><?php echo e($attempt->correct_answers); ?>/<?php echo e($attempt->total_questions); ?></td>
                    <td><?php echo e($attempt->percentage); ?>%</td>
                    <td><span class="badge bg-<?php echo e($attempt->passed ? 'success' : 'danger'); ?>"><?php echo e($attempt->passed ? 'Passed' : 'Failed'); ?></span></td>
                    <td><?php echo e($attempt->submitted_at?->format('d M Y H:i')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer"><?php echo e($attempts->links()); ?></div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/show.blade.php ENDPATH**/ ?>