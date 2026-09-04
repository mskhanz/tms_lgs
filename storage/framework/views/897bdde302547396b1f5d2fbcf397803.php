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
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" style="white-space: pre-line;"><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
<div class="alert alert-danger alert-dismissible fade show"><?php echo e($message); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

<?php
    $activeQuestionCount = $quiz->questions->filter(fn ($q) => $q->isActive())->count();
    $inactiveQuestionCount = $quiz->questions->count() - $activeQuestionCount;
?>

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-primary"><?php echo e($activeQuestionCount); ?></h3><small class="text-muted">Active Questions</small><?php if($inactiveQuestionCount): ?><div class="small text-secondary mt-1"><?php echo e($inactiveQuestionCount); ?> inactive</div><?php endif; ?></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-success"><?php echo e($quiz->totalMarks()); ?></h3><small class="text-muted">Total Marks</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-info"><?php echo e($quiz->duration_minutes ?? '∞'); ?></h3><small class="text-muted">Minutes</small></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center"><h3 class="text-warning"><?php echo e($quiz->passing_percentage); ?>%</h3><small class="text-muted">Passing Score</small></div></div></div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="<?php echo e(route('admin.quizzes.edit', $quiz)); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit Settings</a>
    <a href="<?php echo e(route('admin.quizzes.results', $quiz)); ?>" class="btn btn-outline-success"><i class="bi bi-bar-chart me-1"></i>Results</a>
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
                <div class="border rounded p-3 mb-3 <?php echo e($question->isActive() ? '' : 'bg-light border-secondary'); ?>">
                    <div class="d-flex justify-content-between gap-3">
                        <div class="<?php echo e($question->isActive() ? '' : 'opacity-75'); ?>">
                            <?php if($question->part): ?><span class="badge bg-light text-dark mb-2"><?php echo e($question->part); ?></span><?php endif; ?>
                            <?php if (! ($question->isActive())): ?>
                            <span class="badge bg-secondary mb-2">Inactive</span>
                            <?php endif; ?>
                            <p class="mb-2"><strong>Q<?php echo e($index + 1); ?>.</strong> <?php echo e($question->question_text); ?></p>
                            <ul class="list-unstyled ms-3 mb-0">
                                <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="<?php echo e($opt->is_correct ? 'text-success fw-bold' : ''); ?>">
                                    <?php echo e($opt->is_correct ? '✓' : '○'); ?> <?php echo e($opt->option_text); ?>

                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <a href="<?php echo e(route('admin.quizzes.questions.edit', [$quiz, $question])); ?>" class="btn btn-sm btn-outline-primary" title="Edit this question">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="<?php echo e(route('admin.quizzes.questions.toggle-status', [$quiz, $question])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-outline-<?php echo e($question->isActive() ? 'warning' : 'success'); ?> w-100" title="<?php echo e($question->isActive() ? 'Deactivate' : 'Activate'); ?> this question">
                                    <i class="bi bi-<?php echo e($question->isActive() ? 'pause-circle' : 'play-circle'); ?>"></i>
                                    <?php echo e($question->isActive() ? 'Deactivate' : 'Activate'); ?>

                                </button>
                            </form>
                            <form action="<?php echo e(route('admin.quizzes.questions.destroy', [$quiz, $question])); ?>" method="POST" onsubmit="return confirm('Delete this question?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger w-100" title="Delete this question"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center py-3">No questions yet. Import an Excel file or add one below.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-file-earmark-excel me-2"></i>Import MSQs (Excel)</h5></div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Upload an <strong>.xlsx</strong> file with MSQs and the answer key.
                    Required columns: <code>Question</code>, <code>Option A</code>, <code>Option B</code>, <code>Answer Key</code>.
                    Optional: <code>Part</code>, <code>Option C</code>, <code>Option D</code>, <code>Marks</code>.
                    The answer key can also be a second sheet named <strong>Answer Key</strong> with <code>Question No</code> and <code>Answer Key</code>.
                </p>
                <a href="<?php echo e(route('admin.quizzes.questions.template')); ?>" class="btn btn-outline-success btn-sm mb-3">
                    <i class="bi bi-download me-1"></i>Download template
                </a>
                <form method="POST" action="<?php echo e(route('admin.quizzes.questions.import', $quiz)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <input type="file" name="file" class="form-control" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="replace_existing" value="1" id="replace_existing">
                        <label class="form-check-label" for="replace_existing">Replace existing questions</label>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-upload me-1"></i>Import from Excel
                    </button>
                </form>
            </div>
        </div>

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