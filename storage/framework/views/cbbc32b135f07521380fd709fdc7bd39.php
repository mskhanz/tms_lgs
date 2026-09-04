<?php $__env->startSection('title', 'Edit Question'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $existingOptions = $question->options->values();
    $slotCount = max(4, $existingOptions->count());
    $oldOptions = old('options');
    $oldCorrect = collect(old('correct_options', $existingOptions->filter->is_correct->keys()->all()))
        ->map(fn ($index) => (int) $index)
        ->all();
?>

<div class="page-header">
    <h1><i class="bi bi-pencil me-2"></i>Edit Question</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.quizzes.index')); ?>">Quizzes</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.quizzes.show', $quiz)); ?>"><?php echo e($quiz->title); ?></a></li>
            <li class="breadcrumb-item active">Edit Question</li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo e($quiz->title); ?></h5>
                <?php if (! ($question->isActive())): ?>
                <span class="badge bg-secondary">Inactive</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.quizzes.questions.update', [$quiz, $question])); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label class="form-label">Part / Section</label>
                        <input type="text" name="part" class="form-control <?php $__errorArgs = ['part'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('part', $question->part)); ?>" placeholder="e.g. Part-I">
                        <?php $__errorArgs = ['part'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Question *</label>
                        <textarea name="question_text" class="form-control <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4" required><?php echo e(old('question_text', $question->question_text)); ?></textarea>
                        <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Marks *</label>
                        <input type="number" name="marks" class="form-control <?php $__errorArgs = ['marks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('marks', $question->marks)); ?>" min="1" max="100" required>
                        <?php $__errorArgs = ['marks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <p class="fw-semibold mb-2">Options and answer key</p>
                    <p class="small text-muted mb-3">Tick every correct option. At least two options and one correct answer are required.</p>
                    <?php $__errorArgs = ['options'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mb-2"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['correct_option'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mb-2"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <?php for($i = 0; $i < $slotCount; $i++): ?>
                    <?php
                        $option = $existingOptions->get($i);
                        $optionText = is_array($oldOptions) ? ($oldOptions[$i] ?? '') : old('options.'.$i, $option->option_text ?? '');
                        $isCorrect = in_array($i, $oldCorrect, true);
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Option <?php echo e(chr(65 + $i)); ?><?php echo e($i < 2 ? ' *' : ''); ?></label>
                        <div class="input-group">
                            <input type="text" name="options[<?php echo e($i); ?>]" class="form-control"
                                   value="<?php echo e($optionText); ?>" <?php echo e($i < 2 ? 'required' : ''); ?>>
                            <div class="input-group-text">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="correct_options[]" value="<?php echo e($i); ?>"
                                           id="correct_option_<?php echo e($i); ?>" <?php echo e($isCorrect ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="correct_option_<?php echo e($i); ?>">Correct</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Question
                        </button>
                        <a href="<?php echo e(route('admin.quizzes.show', $quiz)); ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/edit-question.blade.php ENDPATH**/ ?>