<?php
    $attendanceEnabled = (bool) old('attendance_enabled', $model->attendance_enabled ?? false);
    $minAttendance = old('min_attendance_percentage', $model->min_attendance_percentage ?? '');
?>

<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Attendance settings</h5>
    </div>
    <div class="card-body">
        <div class="form-check form-switch mb-3">
            <input type="hidden" name="attendance_enabled" value="0">
            <input class="form-check-input"
                   type="checkbox"
                   role="switch"
                   name="attendance_enabled"
                   id="attendance_enabled"
                   value="1"
                   <?php echo e($attendanceEnabled ? 'checked' : ''); ?>>
            <label class="form-check-label fw-semibold" for="attendance_enabled">
                Enable attendance tracking
            </label>
        </div>
        <p class="text-muted small mb-3">
            When enabled, <?php echo e($context === 'batch' ? 'this batch' : 'batches under this program'); ?> can record session-wise trainee attendance.
        </p>
        <div class="mb-0">
            <label for="min_attendance_percentage" class="form-label fw-semibold">
                Minimum attendance (%)
            </label>
            <input type="number"
                   name="min_attendance_percentage"
                   id="min_attendance_percentage"
                   class="form-control <?php $__errorArgs = ['min_attendance_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e($minAttendance); ?>"
                   min="0"
                   max="100"
                   placeholder="e.g. 75">
            <?php $__errorArgs = ['min_attendance_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="form-text">Optional minimum attendance required for completion or certificates.</div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/partials/attendance-settings.blade.php ENDPATH**/ ?>