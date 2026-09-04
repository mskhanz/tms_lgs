<?php $__env->startSection('title', 'Activity Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="bi bi-activity me-2"></i>Activity Logs</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Activity Logs</li>
        </ol>
    </nav>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.activity-logs.index')); ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Action, user, or IP" value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Module</label>
                <select name="log_name" class="form-select">
                    <option value="">All modules</option>
                    <?php $__currentLoopData = $logNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($name); ?>" <?php echo e(request('log_name') == $name ? 'selected' : ''); ?>><?php echo e(ucfirst($name)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">User</label>
                <select name="user_id" class="form-select">
                    <option value="">All users</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php echo e((string) request('user_id') === (string) $user->id ? 'selected' : ''); ?>>
                            <?php echo e($user->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From</label>
                <input type="date" name="from" class="form-control" value="<?php echo e(request('from')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">To</label>
                <input type="date" name="to" class="form-control" value="<?php echo e(request('to')); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>IP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($logs->firstItem() + $loop->index); ?></td>
                        <td>
                            <div><?php echo e($log->created_at->format('d M Y')); ?></div>
                            <small class="text-muted"><?php echo e($log->created_at->format('h:i:s A')); ?></small>
                        </td>
                        <td>
                            <?php if($log->user): ?>
                                <div class="fw-semibold"><?php echo e($log->user->name); ?></div>
                                <small class="text-muted"><?php echo e($log->user->email); ?></small>
                            <?php else: ?>
                                <span class="text-muted">System / guest</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($log->description); ?></td>
                        <td>
                            <?php if($log->log_name): ?>
                                <span class="badge bg-light text-dark text-uppercase"><?php echo e($log->log_name); ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><code><?php echo e($log->ip_address ?? '—'); ?></code></td>
                        <td>
                            <a href="<?php echo e(route('admin.activity-logs.show', $log->id)); ?>" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">No activity logs found</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('admin.partials.pagination', ['paginator' => $logs, 'label' => 'logs'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/activity-logs/index.blade.php ENDPATH**/ ?>