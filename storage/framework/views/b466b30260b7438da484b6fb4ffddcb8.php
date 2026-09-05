<?php $__env->startSection('title', 'Change Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-key me-2"></i>Change Password</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <?php if(auth()->user()->isTrainee()): ?>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('trainee.profile.show')); ?>">Profile</a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('account.profile')); ?>">Profile</a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active">Password</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(auth()->user()->isTrainee() ? route('trainee.profile.show') : route('account.profile')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <p class="text-muted">Enter your current password, then choose a new one. Other signed-in devices will be logged out.</p>

                <form method="POST" action="<?php echo e(route('account.password.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label class="form-label">Current password <span class="text-danger">*</span></label>
                        <input type="password"
                               name="current_password"
                               class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required
                               autocomplete="current-password">
                        <?php $__errorArgs = ['current_password'];
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
                        <label class="form-label">New password <span class="text-danger">*</span></label>
                        <input type="password"
                               name="password"
                               class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required
                               autocomplete="new-password">
                        <div class="form-text">At least 8 characters, and different from your current password.</div>
                        <?php $__errorArgs = ['password'];
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

                    <div class="mb-4">
                        <label class="form-label">Confirm new password <span class="text-danger">*</span></label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required
                               autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-shield-check me-2"></i>Update password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/account/password.blade.php ENDPATH**/ ?>