<?php $__env->startSection('title', 'Online Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-broadcast me-2"></i>Online Users</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Online Users</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.login-history.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-clock-history me-2"></i>Login history
            </a>
            <a href="<?php echo e(route('admin.online-users.index')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Currently online</div>
                    <div class="fs-3 fw-semibold"><?php echo e($onlineCount); ?></div>
                </div>
                <span class="online-dot"></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Open sessions</div>
                <div class="fs-3 fw-semibold"><?php echo e($openCount); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Logins today</div>
                <div class="fs-3 fw-semibold"><?php echo e($loginsToday); ?></div>
            </div>
        </div>
    </div>
</div>

<p class="text-muted small mb-3">
    A user is shown as online if they were active in the last <?php echo e((int) config('activity.online_minutes', 5)); ?> minutes.
    This page refreshes every 30 seconds.
</p>

<div class="card mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong>Online now</strong>
        <span class="badge bg-success"><?php echo e($onlineCount); ?></span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Logged in</th>
                        <th>Duration</th>
                        <th>Last activity</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if($session->user && $session->user->photoUrl()): ?>
                                    <img src="<?php echo e($session->user->photoUrl()); ?>" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                                <?php else: ?>
                                    <div style="width: 40px; height: 40px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; margin-right: 12px;">
                                        <?php echo e(strtoupper(substr($session->user->name ?? 'U', 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold">
                                        <span class="online-dot me-1"></span>
                                        <?php echo e($session->user->name ?? 'Unknown user'); ?>

                                    </div>
                                    <small class="text-muted"><?php echo e($session->user->email ?? ''); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e(ucfirst($session->user->user_type ?? '—')); ?></span>
                        </td>
                        <td><?php echo e($session->logged_in_at?->format('d M Y, h:i A')); ?></td>
                        <td class="fw-semibold"><?php echo e($session->durationLabel()); ?></td>
                        <td><?php echo e($session->last_activity_at?->diffForHumans()); ?></td>
                        <td><code><?php echo e($session->ip_address ?? '—'); ?></code></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-wifi-off" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">No users are online right now</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if($idleSessions->isNotEmpty()): ?>
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong>Idle sessions</strong>
        <span class="badge bg-warning text-dark"><?php echo e($idleSessions->count()); ?></span>
    </div>
    <div class="card-body">
        <p class="text-muted small">These users still have an open session but have been inactive for more than <?php echo e((int) config('activity.online_minutes', 5)); ?> minutes.</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Logged in</th>
                        <th>Duration</th>
                        <th>Last activity</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $idleSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?php echo e($session->user->name ?? 'Unknown user'); ?></div>
                            <small class="text-muted"><?php echo e($session->user->email ?? ''); ?></small>
                        </td>
                        <td><?php echo e($session->logged_in_at?->format('d M Y, h:i A')); ?></td>
                        <td><?php echo e($session->durationLabel()); ?></td>
                        <td><?php echo e($session->last_activity_at?->diffForHumans()); ?></td>
                        <td><code><?php echo e($session->ip_address ?? '—'); ?></code></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
setTimeout(function () {
    window.location.reload();
}, 30000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/online-users/index.blade.php ENDPATH**/ ?>