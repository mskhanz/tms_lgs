<?php $__env->startSection('title', 'My Profile'); ?>

<?php
    $photo = $user->photoUrl();
    $roleNames = $user->roles->pluck('display_name')->filter()->values();
    $trainer = $user->trainer;
?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-person-badge me-2"></i>My Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo e(route('account.profile.edit')); ?>" class="btn btn-success">
                <i class="bi bi-pencil me-2"></i>Edit profile
            </a>
            <a href="<?php echo e(route('account.password.edit')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-key me-2"></i>Change password
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <?php if($photo): ?>
                    <img src="<?php echo e($photo); ?>" alt="<?php echo e($user->name); ?>" class="account-avatar mb-3">
                <?php else: ?>
                    <div class="account-avatar account-avatar-fallback mb-3">
                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>
                <h4 class="mb-1"><?php echo e($user->name); ?></h4>
                <p class="text-muted mb-3"><?php echo e($user->email); ?></p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <span class="badge bg-<?php echo e($user->isAdmin() ? 'danger' : ($user->isTrainer() ? 'warning text-dark' : 'secondary')); ?>">
                        <?php echo e(ucfirst($user->user_type)); ?>

                    </span>
                    <span class="badge bg-<?php echo e($user->is_active ? 'success' : 'secondary'); ?>">
                        <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <strong><i class="bi bi-person me-2"></i>Account details</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Full name</div>
                        <div class="fw-semibold"><?php echo e($user->name); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Email</div>
                        <div class="fw-semibold"><?php echo e($user->email); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">User type</div>
                        <div class="fw-semibold"><?php echo e(ucfirst($user->user_type)); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Roles</div>
                        <div>
                            <?php $__empty_1 = true; $__currentLoopData = $roleNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <span class="badge bg-primary me-1"><?php echo e($role); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-muted">No roles assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Email verified</div>
                        <div class="fw-semibold"><?php echo e($user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not verified'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Member since</div>
                        <div class="fw-semibold"><?php echo e($user->created_at->format('d M Y')); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Last login</div>
                        <div class="fw-semibold"><?php echo e($lastLogin?->logged_in_at?->format('d M Y, h:i A') ?? '—'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Current session</div>
                        <div class="fw-semibold">
                            <?php if($currentSession): ?>
                                <?php echo e($currentSession->durationLabel()); ?>

                                <span class="badge bg-<?php echo e($currentSession->isOnline() ? 'success' : 'warning text-dark'); ?> ms-1">
                                    <?php echo e($currentSession->statusLabel()); ?>

                                </span>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($trainer): ?>
        <div class="card">
            <div class="card-header bg-light">
                <strong><i class="bi bi-person-video3 me-2"></i>Trainer details</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Designation</div>
                        <div class="fw-semibold"><?php echo e($trainer->designation ?? '—'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Organization</div>
                        <div class="fw-semibold"><?php echo e($trainer->organization ?? '—'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Phone</div>
                        <div class="fw-semibold"><?php echo e($trainer->phone ?? '—'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Expertise</div>
                        <div class="fw-semibold"><?php echo e($trainer->expertise ?? '—'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .account-avatar,
    .account-avatar-fallback {
        width: 112px;
        height: 112px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #dee2e6;
        margin-left: auto;
        margin-right: auto;
    }
    .account-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #10b981;
        color: #fff;
        font-size: 2.25rem;
        font-weight: 700;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/account/profile.blade.php ENDPATH**/ ?>