<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="notifications-page">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <h1 class="mb-1"><i class="bi bi-bell me-2"></i>Notifications</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(auth()->user()->isTrainee() ? route('trainee.dashboard') : route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
            </nav>
        </div>
        <?php if($unreadCount > 0): ?>
        <form method="POST" action="<?php echo e(route('notifications.mark-all-read')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-success btn-sm">
                <i class="bi bi-check2-all me-1"></i>Mark all as read
            </button>
        </form>
        <?php endif; ?>
    </div>

    <div class="notifications-summary mb-4">
        <div class="notifications-summary-card">
            <span class="label">Unread</span>
            <strong class="<?php echo e($unreadCount > 0 ? 'text-danger' : 'text-success'); ?>"><?php echo e(number_format($unreadCount)); ?></strong>
        </div>
        <div class="notifications-summary-card">
            <span class="label">Total</span>
            <strong><?php echo e(number_format($notifications->total())); ?></strong>
        </div>
    </div>

    <div class="card border-0 shadow-sm notifications-list-card">
        <div class="card-body p-0">
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('notifications.read', $notification)); ?>"
               class="notification-list-item <?php echo e($notification->isUnread() ? 'unread' : ''); ?>">
                <div class="notification-list-icon">
                    <i class="bi bi-<?php echo e($notification->icon()); ?>"></i>
                </div>
                <div class="notification-list-content">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="fw-semibold text-dark"><?php echo e($notification->title); ?></div>
                        <?php if($notification->isUnread()): ?>
                        <span class="badge bg-danger notification-unread-dot">New</span>
                        <?php endif; ?>
                    </div>
                    <p class="mb-1 text-muted small"><?php echo e($notification->message); ?></p>
                    <div class="text-muted notification-time">
                        <i class="bi bi-clock me-1"></i><?php echo e($notification->created_at->diffForHumans()); ?>

                        · <?php echo e($notification->created_at->format('d M Y, h:i A')); ?>

                    </div>
                </div>
                <div class="notification-list-arrow">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="notifications-empty">
                <i class="bi bi-bell-slash"></i>
                <p class="mb-0">No notifications yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($notifications->hasPages()): ?>
    <div class="mt-4 d-flex justify-content-center">
        <?php echo e($notifications->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .notifications-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .notifications-summary-card {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 12px;
        padding: 0.9rem 1.15rem;
        min-width: 140px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
    }

    .notifications-summary-card .label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 0.2rem;
    }

    .notifications-summary-card strong {
        font-size: 1.35rem;
    }

    .notifications-list-card {
        border-radius: 14px;
        overflow: hidden;
    }

    .notification-list-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.25rem;
        text-decoration: none;
        color: inherit;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .notification-list-item:last-child {
        border-bottom: none;
    }

    .notification-list-item:hover {
        background: #f8fafc;
    }

    .notification-list-item.unread {
        background: #f0fdf4;
        border-left: 4px solid #10b981;
    }

    .notification-list-item.unread:hover {
        background: #ecfdf5;
    }

    .notification-list-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #ecfdf5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .notification-list-item.unread .notification-list-icon {
        background: #d1fae5;
        color: #047857;
    }

    .notification-list-content {
        flex: 1;
        min-width: 0;
    }

    .notification-list-arrow {
        color: #94a3b8;
        padding-top: 0.35rem;
    }

    .notification-time {
        font-size: 0.75rem;
    }

    .notification-unread-dot {
        font-size: 0.65rem;
        flex-shrink: 0;
    }

    .notifications-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
    }

    .notifications-empty i {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.75rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/notifications/index.blade.php ENDPATH**/ ?>