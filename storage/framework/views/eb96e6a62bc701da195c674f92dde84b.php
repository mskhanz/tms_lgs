<form method="POST" action="<?php echo e($quiz ? route('admin.quizzes.update', $quiz) : route('admin.quizzes.store')); ?>">
    <?php echo csrf_field(); ?>
    <?php if($quiz): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Quiz Title *</label>
            <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('title', $quiz->title ?? '')); ?>" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-4">
            <label class="form-label">Duration (minutes)</label>
            <input type="number" name="duration_minutes" class="form-control" min="1"
                   value="<?php echo e(old('duration_minutes', $quiz->duration_minutes ?? 90)); ?>" placeholder="e.g. 90">
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $quiz->description ?? '')); ?></textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Assign quiz to *</label>
            <div class="d-flex flex-wrap gap-4 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="assign_to" id="assign_to_program" value="program"
                           <?php echo e(old('assign_to', $quiz->assign_to ?? 'program') === 'program' ? 'checked' : ''); ?> required>
                    <label class="form-check-label" for="assign_to_program">Training Program</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="assign_to" id="assign_to_batch" value="batch"
                           <?php echo e(old('assign_to', $quiz->assign_to ?? '') === 'batch' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="assign_to_batch">Training Batch</label>
                </div>
            </div>
            <div class="form-text">Only trainees enrolled in the selected program or batch will see and attempt this quiz.</div>
            <?php $__errorArgs = ['assign_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-6" id="programAssignmentWrap">
            <label class="form-label">Training Program *</label>
            <select name="training_program_id" id="training_program_id" class="form-select <?php $__errorArgs = ['training_program_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">-- Select Program --</option>
                <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($program->id); ?>" <?php echo e((string) old('training_program_id', $quiz->training_program_id ?? '') === (string) $program->id ? 'selected' : ''); ?>>
                        <?php echo e($program->code); ?> — <?php echo e($program->title); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['training_program_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-6" id="batchAssignmentWrap">
            <label class="form-label">Training Batch *</label>
            <select name="training_batch_id" id="training_batch_id" class="form-select <?php $__errorArgs = ['training_batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">-- Select Batch --</option>
                <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($batch->id); ?>" <?php echo e((string) old('training_batch_id', $quiz->training_batch_id ?? '') === (string) $batch->id ? 'selected' : ''); ?>>
                        <?php echo e($batch->batch_code); ?> — <?php echo e($batch->trainingProgram->title ?? 'Program'); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['training_batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-3">
            <label class="form-label">Passing % *</label>
            <input type="number" name="passing_percentage" class="form-control" min="1" max="100"
                   value="<?php echo e(old('passing_percentage', $quiz->passing_percentage ?? 50)); ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Max Attempts *</label>
            <input type="number" name="max_attempts" class="form-control" min="1" max="10"
                   value="<?php echo e(old('max_attempts', $quiz->max_attempts ?? 1)); ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Available From</label>
            <input type="datetime-local" name="available_from" class="form-control"
                   value="<?php echo e(old('available_from', $quiz?->available_from?->format('Y-m-d\TH:i'))); ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Available Until</label>
            <input type="datetime-local" name="available_until" class="form-control"
                   value="<?php echo e(old('available_until', $quiz?->available_until?->format('Y-m-d\TH:i'))); ?>">
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="shuffle_questions" value="1" class="form-check-input" id="shuffle_questions"
                       <?php echo e(old('shuffle_questions', $quiz?->shuffle_questions ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="shuffle_questions">Shuffle Questions</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="shuffle_options" value="1" class="form-check-input" id="shuffle_options"
                       <?php echo e(old('shuffle_options', $quiz?->shuffle_options ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="shuffle_options">Shuffle Options</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="show_results" value="1" class="form-check-input" id="show_results"
                       <?php echo e(old('show_results', $quiz?->show_results ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="show_results">Show Results to Trainee</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                       <?php echo e(old('is_active', $quiz?->is_active ?? false) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="is_active">Active (visible to trainees)</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i><?php echo e($quiz ? 'Update Quiz' : 'Create Quiz'); ?>

        </button>
        <a href="<?php echo e(route('admin.quizzes.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const programWrap = document.getElementById('programAssignmentWrap');
    const batchWrap = document.getElementById('batchAssignmentWrap');
    const programSelect = document.getElementById('training_program_id');
    const batchSelect = document.getElementById('training_batch_id');

    function toggleAssignment() {
        const assignTo = document.querySelector('input[name="assign_to"]:checked')?.value;
        const isProgram = assignTo === 'program';

        programWrap.classList.toggle('d-none', !isProgram);
        batchWrap.classList.toggle('d-none', isProgram);
        programSelect.required = isProgram;
        batchSelect.required = !isProgram;
        programSelect.disabled = !isProgram;
        batchSelect.disabled = isProgram;
    }

    document.querySelectorAll('input[name="assign_to"]').forEach(function (input) {
        input.addEventListener('change', toggleAssignment);
    });

    toggleAssignment();
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/_form.blade.php ENDPATH**/ ?>