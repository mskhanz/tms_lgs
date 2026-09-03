

<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<?php if($errors->any()): ?>
<div class="alert-auth error">
    <i class="bi bi-exclamation-circle-fill"></i>
    <span><?php echo e($errors->first()); ?></span>
</div>
<?php endif; ?>

<?php if(session('success')): ?>
<div class="alert-auth success">
    <i class="bi bi-check-circle-fill"></i>
    <span><?php echo e(session('success')); ?></span>
</div>
<?php endif; ?>

<?php if(session('warning')): ?>
<div class="alert-auth warning">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span><?php echo e(session('warning')); ?></span>
</div>
<?php endif; ?>

<?php if(session('status')): ?>
<div class="alert-auth success">
    <i class="bi bi-check-circle-fill"></i>
    <span><?php echo e(session('status')); ?></span>
</div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo csrf_field(); ?>

    <div class="form-floating-custom">
        <label for="email">Email Address</label>
        <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" id="email"
                   class="<?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>"
                   value="<?php echo e(old('email')); ?>"
                   placeholder="your@email.com"
                   required autofocus autocomplete="email">
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="form-floating-custom">
        <label for="password">Password</label>
        <div class="input-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" id="password"
                   class="<?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
                   placeholder="••••••••"
                   required autocomplete="current-password">
            <button class="toggle-btn" type="button" id="togglePassword" tabindex="-1" aria-label="Toggle password visibility">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="remember-row">
        <div class="remember-check">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Keep me signed in</label>
        </div>
        <a href="<?php echo e(route('password.request')); ?>" class="forgot-link">Forgot password?</a>
    </div>

    <button type="submit" class="btn-signin">
        <i class="bi bi-box-arrow-in-right"></i>
        Sign In
    </button>
</form>

<div class="auth-register-link">
    Don't have an account? <a href="<?php echo e(route('register')); ?>">Register as Trainee</a>
</div>

<a href="<?php echo e(route('home')); ?>" class="auth-home-link">
    <i class="bi bi-arrow-left"></i> Back to home
</a>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            pwd.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/auth/login.blade.php ENDPATH**/ ?>