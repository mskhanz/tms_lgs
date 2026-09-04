

<?php $__env->startSection('title', 'New Enrollment'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>New Enrollment</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.enrollments.index')); ?>">Enrollments</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<!-- Alerts -->
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if($errors->any()): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <!-- Enrollment Form Card -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-file-earmark-plus me-2"></i>Enrollment Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.enrollments.store')); ?>" id="enrollmentForm">
                    <?php echo csrf_field(); ?>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <label for="trainee_ids" class="form-label fw-semibold mb-0">
                                <i class="bi bi-people me-1"></i>Select Trainees <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllTrainees">
                                    <i class="bi bi-check2-all me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clearTrainees">
                                    <i class="bi bi-x-lg me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                        <select name="trainee_ids[]" id="trainee_ids" class="form-select" multiple required>
                            <?php $__empty_1 = true; $__currentLoopData = $trainees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($trainee->id); ?>"
                                        data-email="<?php echo e($trainee->email); ?>"
                                        data-cnic="<?php echo e($trainee->traineeProfile->cnic_no ?? 'N/A'); ?>"
                                        data-designation="<?php echo e($trainee->traineeProfile->designation ?? 'N/A'); ?>"
                                        data-photo="<?php echo e($trainee->photoUrl()); ?>"
                                        data-initial="<?php echo e(strtoupper(substr($trainee->name, 0, 1))); ?>"
                                        <?php echo e(in_array($trainee->id, old('trainee_ids', [])) ? 'selected' : ''); ?>>
                                    <?php echo e($trainee->name); ?> - <?php echo e($trainee->traineeProfile->cnic_no ?? $trainee->email); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="" disabled>No unenrolled trainees available</option>
                            <?php endif; ?>
                        </select>
                        <?php $__errorArgs = ['trainee_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['trainee_ids.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">
                            Select one or more trainees, or use <strong>Select All</strong> to enroll every available trainee.
                            <span class="ms-1 text-primary fw-semibold" id="selectedTraineeCount">0 selected</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="training_batch_id" class="form-label fw-semibold">
                            <i class="bi bi-calendar3 me-1"></i>Select Training Batch <span class="text-danger">*</span>
                        </label>
                        <select name="training_batch_id" id="training_batch_id" class="form-select" required>
                            <option value="">-- Select Training Batch --</option>
                            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($batch->id); ?>" 
                                        data-program="<?php echo e($batch->trainingProgram->title); ?>"
                                        data-code="<?php echo e($batch->batch_code); ?>"
                                        data-start="<?php echo e(\Carbon\Carbon::parse($batch->start_date)->format('d M, Y')); ?>"
                                        data-end="<?php echo e(\Carbon\Carbon::parse($batch->end_date)->format('d M, Y')); ?>"
                                        data-seats="<?php echo e($batch->total_seats); ?>"
                                        data-filled="<?php echo e($batch->seats_filled); ?>"
                                        data-available="<?php echo e($batch->total_seats - $batch->seats_filled); ?>"
                                        <?php echo e(old('training_batch_id') == $batch->id ? 'selected' : ''); ?>>
                                    <?php echo e($batch->trainingProgram->title); ?> - <?php echo e($batch->batch_code); ?>

                                    (<?php echo e($batch->total_seats - $batch->seats_filled); ?> seats available)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['training_batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <!-- Batch Info Display -->
                        <div id="batchInfo" class="mt-3 p-3 bg-light rounded d-none">
                            <h6 class="mb-2"><i class="bi bi-info-circle me-1"></i>Batch Details</h6>
                            <div class="row small">
                                <div class="col-md-6">
                                    <strong>Program:</strong> <span id="batchProgram">-</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Batch Code:</strong> <span id="batchCode">-</span>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <strong>Start Date:</strong> <span id="batchStart">-</span>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <strong>End Date:</strong> <span id="batchEnd">-</span>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <strong>Seats:</strong> 
                                    <span id="batchSeats">-</span> total, 
                                    <span id="batchFilled">-</span> filled, 
                                    <span id="batchAvailable" class="text-success fw-semibold">-</span> available
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="enrollment_date" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check me-1"></i>Enrollment Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               name="enrollment_date" 
                               id="enrollment_date" 
                               class="form-control" 
                               value="<?php echo e(old('enrollment_date', date('Y-m-d'))); ?>"
                               required>
                        <?php $__errorArgs = ['enrollment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label for="remarks" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1"></i>Remarks
                        </label>
                        <textarea name="remarks" 
                                  id="remarks" 
                                  class="form-control" 
                                  rows="4"
                                  placeholder="Enter any additional notes or remarks..."><?php echo e(old('remarks')); ?></textarea>
                        <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Optional: Add any special notes about this enrollment</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitEnrollment">
                            <i class="bi bi-check-circle me-2"></i>Enroll Selected Trainees
                        </button>
                        <a href="<?php echo e(route('admin.enrollments.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Help Card -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-question-circle me-2"></i>Enrollment Guidelines</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary"><i class="bi bi-check-circle me-1"></i>Before Enrolling:</h6>
                    <ul class="small mb-0">
                        <li>Select one or more trainees, or use Select All</li>
                        <li>Verify batch has enough available seats</li>
                        <li>Check batch schedule doesn't conflict</li>
                        <li>Confirm trainee meets prerequisites</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-success"><i class="bi bi-info-circle me-1"></i>What Happens Next:</h6>
                    <ul class="small mb-0">
                        <li>Trainee receives email notification</li>
                        <li>Batch seat count is updated</li>
                        <li>Enrollment appears in trainee dashboard</li>
                        <li>Attendance tracking begins</li>
                    </ul>
                </div>

                <div class="alert alert-warning small mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <strong>Note:</strong> Once enrolled, trainees will be able to see the training program details and schedule in their dashboard.
                </div>
            </div>
        </div>

        <!-- Quick Stats Card -->
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Quick Statistics</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Enrolled trainees:</span>
                    <span class="fw-semibold"><?php echo e($enrolledTrainees->count()); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Unenrolled trainees:</span>
                    <span class="fw-semibold"><?php echo e($trainees->count()); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Active Batches:</span>
                    <span class="fw-semibold"><?php echo e($batches->count()); ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Total Seats:</span>
                    <span class="fw-semibold"><?php echo e($batches->sum('total_seats')); ?></span>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-people me-2"></i>Enrolled trainees</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $enrolledTrainees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrolledTrainee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $currentEnrollment = $enrolledTrainee->enrollments->first(); ?>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 <?php echo e(! $loop->last ? 'border-bottom' : ''); ?>">
                        <?php if($enrolledTrainee->photoUrl()): ?>
                            <img src="<?php echo e($enrolledTrainee->photoUrl()); ?>" alt="<?php echo e($enrolledTrainee->name); ?>" class="trainee-info-photo" style="width: 36px; height: 36px;">
                        <?php else: ?>
                            <span class="trainee-info-fallback" style="width: 36px; height: 36px; font-size: 0.85rem;"><?php echo e(strtoupper(substr($enrolledTrainee->name, 0, 1))); ?></span>
                        <?php endif; ?>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small text-truncate"><?php echo e($enrolledTrainee->name); ?></div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                <?php echo e($currentEnrollment?->trainingBatch?->trainingProgram?->title ?? 'N/A'); ?>

                                <?php if($currentEnrollment?->trainingBatch?->batch_code): ?>
                                    · <?php echo e($currentEnrollment->trainingBatch->batch_code); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted text-center small py-3 mb-0">No trainees enrolled yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<style>
    .form-label.fw-semibold {
        color: #047857;
    }
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #34d399 100%);
        transform: translateY(-1px);
    }
    .card-header.bg-info {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%) !important;
    }
    .card-header.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    }
    .select2-container {
        width: 100% !important;
    }
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 46px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 6px 8px;
    }
    .select2-results__option .trainee-option,
    .select2-selection__rendered .trainee-option {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    img.trainee-option-photo,
    .trainee-option-fallback {
        width: 32px !important;
        height: 32px !important;
        max-width: 32px !important;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        display: inline-block;
        vertical-align: middle;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 44px;
    }
    .select2-results__option {
        padding: 6px 12px;
    }
    .trainee-option {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .trainee-option-photo,
    .trainee-option-fallback {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }
    .trainee-option-fallback {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #10b981;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
    }
    .trainee-info-photo,
    .trainee-info-fallback {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }
    .trainee-info-fallback {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #10b981;
        color: #fff;
        font-size: 1.25rem;
        font-weight: 700;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {
    const traineeSelect = document.getElementById('trainee_ids');
    const batchSelect = document.getElementById('training_batch_id');
    const batchInfo = document.getElementById('batchInfo');
    const selectedCountEl = document.getElementById('selectedTraineeCount');

    const traineePhotos = <?php echo json_encode($trainees->mapWithKeys(fn ($trainee) => [(string) $trainee->id => $trainee->photoUrl()]), 15, 512) ?>;
    const traineeInitials = <?php echo json_encode($trainees->mapWithKeys(fn ($trainee) => [(string) $trainee->id => strtoupper(substr($trainee->name, 0, 1))])) ?>;

    function traineeAvatar(option) {
        const id = option && option.id ? String(option.id) : '';
        const photo = traineePhotos[id] || (option.element ? option.element.getAttribute('data-photo') : '') || '';
        const initial = traineeInitials[id] || (option.element ? option.element.getAttribute('data-initial') : '') || '';
        if (photo) {
            return '<img src="' + photo + '" class="trainee-option-photo" alt="" width="32" height="32">';
        }
        return '<span class="trainee-option-fallback">' + initial + '</span>';
    }

    function formatTrainee(option) {
        if (!option.id) {
            return option.text;
        }
        return $('<span class="trainee-option">' + traineeAvatar(option) + '<span>' + option.text + '</span></span>');
    }

    const $traineeSelect = $('#trainee_ids').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Search and select trainees...',
        closeOnSelect: false,
        allowClear: true,
        templateResult: formatTrainee,
        templateSelection: formatTrainee,
        dropdownParent: $(document.body)
    });

    function updateSelectedCount() {
        const count = $traineeSelect.val()?.length || 0;
        selectedCountEl.textContent = count + ' selected';
    }

    $('#selectAllTrainees').on('click', function () {
        const allIds = Array.from(traineeSelect.options)
            .filter(option => option.value)
            .map(option => option.value);
        $traineeSelect.val(allIds).trigger('change');
    });

    $('#clearTrainees').on('click', function () {
        $traineeSelect.val(null).trigger('change');
    });

    $traineeSelect.on('change', updateSelectedCount);
    updateSelectedCount();

    batchSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('batchProgram').textContent = selected.dataset.program || 'N/A';
            document.getElementById('batchCode').textContent = selected.dataset.code || 'N/A';
            document.getElementById('batchStart').textContent = selected.dataset.start || 'N/A';
            document.getElementById('batchEnd').textContent = selected.dataset.end || 'N/A';
            document.getElementById('batchSeats').textContent = selected.dataset.seats || '0';
            document.getElementById('batchFilled').textContent = selected.dataset.filled || '0';
            document.getElementById('batchAvailable').textContent = selected.dataset.available || '0';

            const available = parseInt(selected.dataset.available || 0);
            if (available <= 0) {
                alert('Warning: This batch is full! No seats available.');
            } else if (available <= 3) {
                document.getElementById('batchAvailable').classList.remove('text-success');
                document.getElementById('batchAvailable').classList.add('text-warning');
            }

            batchInfo.classList.remove('d-none');
        } else {
            batchInfo.classList.add('d-none');
        }
    });

    if (batchSelect.value) {
        batchSelect.dispatchEvent(new Event('change'));
    }

    document.getElementById('enrollmentForm').addEventListener('submit', function(e) {
        const selectedTrainees = $traineeSelect.val()?.length || 0;
        const selected = batchSelect.options[batchSelect.selectedIndex];
        const available = parseInt(selected?.dataset?.available || 0);

        if (selectedTrainees === 0) {
            e.preventDefault();
            alert('Please select at least one trainee.');
            return false;
        }

        if (!batchSelect.value) {
            return true;
        }

        if (available <= 0) {
            e.preventDefault();
            alert('Cannot enroll: This batch is full!');
            return false;
        }

        if (selectedTrainees > available) {
            e.preventDefault();
            alert('Cannot enroll ' + selectedTrainees + ' trainees. Only ' + available + ' seat(s) available in this batch.');
            return false;
        }

        if (selectedTrainees > 1 && !confirm('Enroll ' + selectedTrainees + ' trainees in this batch?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/enrollments/create.blade.php ENDPATH**/ ?>