

<?php $__env->startSection('title', 'Create Training Program'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2"></i>Create Training Program</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.programs.index')); ?>">Programs</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<!-- Alerts -->
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

<form method="POST" action="<?php echo e(route('admin.programs.store')); ?>" id="programForm">
    <?php echo csrf_field(); ?>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label fw-semibold">
                                Program Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('code')); ?>"
                                   placeholder="e.g., TP-2025-001"
                                   required>
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Unique identifier for this program</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="conducting_organization_id" class="form-label fw-semibold">
                                Conducting Organization
                            </label>
                            <select name="conducting_organization_id" 
                                    id="conducting_organization_id" 
                                    class="form-select <?php $__errorArgs = ['conducting_organization_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">-- Select Organization --</option>
                                <?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($org->id); ?>" <?php echo e(old('conducting_organization_id') == $org->id ? 'selected' : ''); ?>>
                                        <?php echo e($org->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['conducting_organization_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">
                            Program Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('title')); ?>"
                               placeholder="Enter program title"
                               required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  rows="4"
                                  placeholder="Detailed description of the training program..."
                                  required><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label fw-semibold">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category" 
                                    id="category" 
                                    class="form-select <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <option value="">-- Select Category --</option>
                                <option value="technical" <?php echo e(old('category') == 'technical' ? 'selected' : ''); ?>>Technical</option>
                                <option value="leadership" <?php echo e(old('category') == 'leadership' ? 'selected' : ''); ?>>Leadership</option>
                                <option value="management" <?php echo e(old('category') == 'management' ? 'selected' : ''); ?>>Management</option>
                                <option value="specialized" <?php echo e(old('category') == 'specialized' ? 'selected' : ''); ?>>Specialized</option>
                                <option value="soft_skills" <?php echo e(old('category') == 'soft_skills' ? 'selected' : ''); ?>>Soft Skills</option>
                                <option value="mid_career_training" <?php echo e(old('category') == 'mid_career_training' ? 'selected' : ''); ?>>Mid Career Training</option>
                                <option value="pre_service_training" <?php echo e(old('category') == 'pre_service_training' ? 'selected' : ''); ?>>Pre-service Training</option>
                                <option value="pre_promotion_training" <?php echo e(old('category') == 'pre_promotion_training' ? 'selected' : ''); ?>>Pre-Promotion Training</option>
                                <option value="others" <?php echo e(old('category') == 'others' ? 'selected' : ''); ?>>Others</option>
                            </select>
                            <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label fw-semibold">
                                Type <span class="text-danger">*</span>
                            </label>
                            <select name="type" 
                                    id="type" 
                                    class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <option value="">-- Select Type --</option>
                                <option value="orientation" <?php echo e(old('type') == 'orientation' ? 'selected' : ''); ?>>Orientation</option>
                                <option value="induction" <?php echo e(old('type') == 'induction' ? 'selected' : ''); ?>>Induction</option>
                                <option value="refresher" <?php echo e(old('type') == 'refresher' ? 'selected' : ''); ?>>Refresher</option>
                                <option value="specialized" <?php echo e(old('type') == 'specialized' ? 'selected' : ''); ?>>Specialized</option>
                                <option value="advanced" <?php echo e(old('type') == 'advanced' ? 'selected' : ''); ?>>Advanced</option>
                            </select>
                            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="budget_allocated" class="form-label fw-semibold">
                                Budget Allocated (PKR)
                            </label>
                            <input type="number" 
                                   name="budget_allocated" 
                                   id="budget_allocated" 
                                   class="form-control <?php $__errorArgs = ['budget_allocated'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('budget_allocated')); ?>"
                                   min="0"
                                   step="0.01"
                                   placeholder="0.00">
                            <?php $__errorArgs = ['budget_allocated'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Duration & Participants Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-clock me-2"></i>Duration & Participants</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="duration_days" class="form-label fw-semibold">
                                Duration (Days) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="duration_days" 
                                   id="duration_days" 
                                   class="form-control <?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('duration_days')); ?>"
                                   min="1"
                                   required>
                            <?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="duration_hours" class="form-label fw-semibold">
                                Duration (Hours) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="duration_hours" 
                                   id="duration_hours" 
                                   class="form-control <?php $__errorArgs = ['duration_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('duration_hours')); ?>"
                                   min="1"
                                   required>
                            <?php $__errorArgs = ['duration_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="min_participants" class="form-label fw-semibold">
                                Min Participants
                            </label>
                            <input type="number" 
                                   name="min_participants" 
                                   id="min_participants" 
                                   class="form-control <?php $__errorArgs = ['min_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('min_participants')); ?>"
                                   min="1">
                            <?php $__errorArgs = ['min_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="max_participants" class="form-label fw-semibold">
                                Max Participants
                            </label>
                            <input type="number" 
                                   name="max_participants" 
                                   id="max_participants" 
                                   class="form-control <?php $__errorArgs = ['max_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('max_participants')); ?>"
                                   min="1">
                            <?php $__errorArgs = ['max_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Additional Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="objectives" class="form-label fw-semibold">
                            Training Objectives
                        </label>
                        <textarea name="objectives" 
                                  id="objectives" 
                                  class="form-control <?php $__errorArgs = ['objectives'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  rows="4"
                                  placeholder="List the main objectives of this training program..."><?php echo e(old('objectives')); ?></textarea>
                        <?php $__errorArgs = ['objectives'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="target_audience" class="form-label fw-semibold">
                            Target Audience
                        </label>
                        <textarea name="target_audience" 
                                  id="target_audience" 
                                  class="form-control <?php $__errorArgs = ['target_audience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  rows="3"
                                  placeholder="Describe the target audience for this program..."><?php echo e(old('target_audience')); ?></textarea>
                        <?php $__errorArgs = ['target_audience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Create Program
                </button>
                <a href="<?php echo e(route('admin.programs.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <?php echo $__env->make('admin.partials.attendance-settings', [
                'model' => $program ?? null,
                'context' => 'program',
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Help Card -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-question-circle me-2"></i>Program Creation Guide</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary"><i class="bi bi-lightbulb me-1"></i>Tips:</h6>
                    <ul class="small mb-3">
                        <li>Use a unique, descriptive program code</li>
                        <li>Provide clear and detailed objectives</li>
                        <li>Specify realistic duration and participant limits</li>
                        <li>Choose appropriate category and type</li>
                    </ul>

                    <h6 class="text-success"><i class="bi bi-info-circle me-1"></i>After Creation:</h6>
                    <ul class="small mb-0">
                        <li>Program will be in "Draft" status</li>
                        <li>Create training batches for enrollment</li>
                        <li>Assign trainers to batches</li>
                        <li>Activate program when ready</li>
                    </ul>
                </div>
            </div>

            <!-- Categories Info -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-tags me-2"></i>Categories Guide</h6>
                </div>
                <div class="card-body small">
                    <div class="mb-2">
                        <strong>Technical:</strong> IT, software, hardware, systems
                    </div>
                    <div class="mb-2">
                        <strong>Leadership:</strong> Management, decision-making
                    </div>
                    <div class="mb-2">
                        <strong>Management:</strong> Administration, planning
                    </div>
                    <div class="mb-2">
                        <strong>Specialized:</strong> Domain-specific skills
                    </div>
                    <div class="mb-2">
                        <strong>Soft Skills:</strong> Communication, teamwork
                    </div>
                    <div class="mb-2">
                        <strong>Mid Career Training:</strong> In-service officers at mid career stage
                    </div>
                    <div class="mb-2">
                        <strong>Pre-service Training:</strong> New inductees before assuming duties
                    </div>
                    <div class="mb-2">
                        <strong>Pre-Promotion Training:</strong> Officers preparing for the next grade
                    </div>
                    <div>
                        <strong>Others:</strong> Miscellaneous programs
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $__env->startPush('styles'); ?>
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
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/programs/create.blade.php ENDPATH**/ ?>