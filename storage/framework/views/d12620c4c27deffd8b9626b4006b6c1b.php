<form method="POST"
      action="<?php echo e($assignment ? route('admin.assignments.update', $assignment) : route('admin.assignments.store')); ?>"
      enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if($assignment): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('title', $assignment->title ?? '')); ?>" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-3">
            <label class="form-label">Total marks *</label>
            <input type="number" name="total_marks" class="form-control <?php $__errorArgs = ['total_marks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('total_marks', $assignment->total_marks ?? 100)); ?>" min="1" max="9999" step="0.01" required>
            <?php $__errorArgs = ['total_marks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-3">
            <label class="form-label">Due date</label>
            <input type="datetime-local" name="due_at" class="form-control"
                   value="<?php echo e(old('due_at', $assignment?->due_at?->format('Y-m-d\TH:i'))); ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Instructions</label>
            <textarea name="instructions" class="form-control" rows="5"><?php echo e(old('instructions', $assignment->instructions ?? '')); ?></textarea>
            <?php $__errorArgs = ['instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-12">
            <label class="form-label">Assign to *</label>
            <div class="d-flex flex-wrap gap-4 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="assign_to" id="assign_to_program" value="program"
                           <?php echo e(old('assign_to', $assignment->assign_to ?? 'program') === 'program' ? 'checked' : ''); ?> required>
                    <label class="form-check-label" for="assign_to_program">Training Program</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="assign_to" id="assign_to_batch" value="batch"
                           <?php echo e(old('assign_to', $assignment->assign_to ?? '') === 'batch' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="assign_to_batch">Training Batch</label>
                </div>
            </div>
            <div class="form-text">Only enrolled trainees in the selected program or batch will see this assignment.</div>
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
                    <option value="<?php echo e($program->id); ?>" <?php echo e((string) old('training_program_id', $assignment->training_program_id ?? '') === (string) $program->id ? 'selected' : ''); ?>>
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
                    <option value="<?php echo e($batch->id); ?>" <?php echo e((string) old('training_batch_id', $assignment->training_batch_id ?? '') === (string) $batch->id ? 'selected' : ''); ?>>
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

        <div class="col-md-4">
            <label class="form-label">Available From</label>
            <input type="datetime-local" name="available_from" class="form-control"
                   value="<?php echo e(old('available_from', $assignment?->available_from?->format('Y-m-d\TH:i'))); ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Available Until</label>
            <input type="datetime-local" name="available_until" class="form-control"
                   value="<?php echo e(old('available_until', $assignment?->available_until?->format('Y-m-d\TH:i'))); ?>">
        </div>
        <div class="col-md-4">
            <div class="form-check mt-4">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                       <?php echo e(old('is_active', $assignment?->is_active ?? false) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="is_active">Active (notify &amp; show to trainees)</label>
            </div>
        </div>

        <?php if($assignment && $assignment->attachments->count()): ?>
        <div class="col-12">
            <label class="form-label">Current materials</label>
            <div class="d-flex flex-column gap-2">
                <?php $__currentLoopData = $assignment->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border rounded p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small mb-1">Material name</label>
                            <input type="text" name="existing_titles[<?php echo e($file->id); ?>]" class="form-control"
                                   value="<?php echo e(old('existing_titles.'.$file->id, $file->title ?: $file->original_name)); ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small mb-1">File</label>
                            <div class="form-control-plaintext small">
                                <i class="bi bi-paperclip me-1"></i><?php echo e($file->original_name); ?>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_attachments[]" value="<?php echo e($file->id); ?>" id="rm_<?php echo e($file->id); ?>">
                                <label class="form-check-label text-danger" for="rm_<?php echo e($file->id); ?>">Remove</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0">Upload materials (Word, PDF, images)</label>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-material-row">
                    <i class="bi bi-plus-circle me-1"></i>Add another file
                </button>
            </div>
            <div id="materials-rows" class="d-flex flex-column gap-2">
                <div class="border rounded p-3 material-row">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small mb-1">Material name *</label>
                            <input type="text" name="materials[0][title]" class="form-control" placeholder="e.g. Guidelines, Template, Sample">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1">File</label>
                            <input type="file" name="materials[0][file]" class="form-control"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger w-100 remove-material-row" title="Remove" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-text mt-2">Add one or more files. Each file should have a name. Allowed: pdf, doc, docx, jpg, png, webp. Max 10 MB each.</div>
            <?php $__errorArgs = ['materials.*.file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['materials.*.title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i><?php echo e($assignment ? 'Update Assignment' : 'Create Assignment'); ?>

        </button>
        <a href="<?php echo e(route('admin.assignments.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
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

    const rowsWrap = document.getElementById('materials-rows');
    const addBtn = document.getElementById('add-material-row');
    let materialIndex = rowsWrap.querySelectorAll('.material-row').length;

    function refreshRemoveButtons() {
        const rows = rowsWrap.querySelectorAll('.material-row');
        rows.forEach(function (row) {
            const btn = row.querySelector('.remove-material-row');
            if (btn) {
                btn.disabled = rows.length <= 1;
            }
        });
    }

    function bindRemove(btn) {
        btn.addEventListener('click', function () {
            const rows = rowsWrap.querySelectorAll('.material-row');
            if (rows.length <= 1) {
                return;
            }
            btn.closest('.material-row').remove();
            refreshRemoveButtons();
        });
    }

    rowsWrap.querySelectorAll('.remove-material-row').forEach(bindRemove);

    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'border rounded p-3 material-row';
        row.innerHTML =
            '<div class="row g-2 align-items-end">' +
                '<div class="col-md-5">' +
                    '<label class="form-label small mb-1">Material name *</label>' +
                    '<input type="text" name="materials[' + materialIndex + '][title]" class="form-control" placeholder="e.g. Guidelines, Template, Sample">' +
                '</div>' +
                '<div class="col-md-6">' +
                    '<label class="form-label small mb-1">File</label>' +
                    '<input type="file" name="materials[' + materialIndex + '][file]" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">' +
                '</div>' +
                '<div class="col-md-1">' +
                    '<button type="button" class="btn btn-outline-danger w-100 remove-material-row" title="Remove"><i class="bi bi-trash"></i></button>' +
                '</div>' +
            '</div>';
        rowsWrap.appendChild(row);
        bindRemove(row.querySelector('.remove-material-row'));
        materialIndex++;
        refreshRemoveButtons();
    });

    refreshRemoveButtons();
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/assignments/_form.blade.php ENDPATH**/ ?>