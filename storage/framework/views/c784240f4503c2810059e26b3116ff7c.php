

<?php $__env->startSection('title', 'Training Enrollments'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-person-check me-2"></i>Training Enrollments</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Enrollments</li>
        </ol>
    </nav>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?php echo e(route('admin.enrollments.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Enrollment
        </a>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Print
        </button>
        <button class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel me-2"></i>Export
        </button>
    </div>
</div>

<!-- Alerts -->
<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.enrollments.index')); ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="enrolled">Enrolled</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="dropped">Dropped</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Trainee name or CNIC...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Program</label>
                <input type="text" name="program" class="form-control" placeholder="Training program...">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                    <a href="<?php echo e(route('admin.enrollments.index')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Enrollments Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Trainee</th>
                        <th>CNIC</th>
                        <th>Training Program</th>
                        <th>Batch</th>
                        <th>Enrollment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($enrollments->firstItem() + $loop->index); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-small me-2">
                                    <?php if($enrollment->trainee->photoUrl()): ?>
                                        <img src="<?php echo e($enrollment->trainee->photoUrl()); ?>"
                                             alt="<?php echo e($enrollment->trainee->name); ?>"
                                             style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;">
                                    <?php else: ?>
                                        <div style="width: 35px; height: 35px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                            <?php echo e(strtoupper(substr($enrollment->trainee->name, 0, 1))); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?php echo e($enrollment->trainee->name); ?></div>
                                    <small class="text-muted"><?php echo e($enrollment->trainee->email); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if($enrollment->trainee->traineeProfile): ?>
                                <?php echo e($enrollment->trainee->traineeProfile->cnic_no ?? 'N/A'); ?>

                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-semibold"><?php echo e($enrollment->trainingBatch->trainingProgram->title); ?></div>
                            <small class="text-muted"><?php echo e($enrollment->trainingBatch->trainingProgram->code); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e($enrollment->trainingBatch->batch_code); ?></span>
                        </td>
                        <td><?php echo e(\Carbon\Carbon::parse($enrollment->enrollment_date)->format('d M, Y')); ?></td>
                        <td>
                            <?php
                                $statusColors = [
                                    'enrolled' => 'primary',
                                    'in_progress' => 'info',
                                    'completed' => 'success',
                                    'dropped' => 'warning',
                                    'failed' => 'danger'
                                ];
                                $color = $statusColors[$enrollment->status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo e($color); ?>"><?php echo e(ucwords(str_replace('_', ' ', $enrollment->status))); ?></span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('admin.enrollments.show', $enrollment->id)); ?>" 
                                   class="btn btn-outline-primary" 
                                   title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#statusModal<?php echo e($enrollment->id); ?>"
                                        title="Update Status">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>

                            <!-- Status Update Modal -->
                            <div class="modal fade" id="statusModal<?php echo e($enrollment->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="<?php echo e(route('admin.enrollments.update-status', $enrollment->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Enrollment Status</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="enrolled" <?php echo e($enrollment->status == 'enrolled' ? 'selected' : ''); ?>>Enrolled</option>
                                                        <option value="in_progress" <?php echo e($enrollment->status == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                                                        <option value="completed" <?php echo e($enrollment->status == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                                        <option value="dropped" <?php echo e($enrollment->status == 'dropped' ? 'selected' : ''); ?>>Dropped</option>
                                                        <option value="failed" <?php echo e($enrollment->status == 'failed' ? 'selected' : ''); ?>>Failed</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Completion Date (if applicable)</label>
                                                    <input type="date" name="completion_date" class="form-control" value="<?php echo e($enrollment->completion_date); ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Remarks</label>
                                                    <textarea name="remarks" class="form-control" rows="3"><?php echo e($enrollment->remarks); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Status</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">No enrollments found</p>
                            <a href="<?php echo e(route('admin.enrollments.create')); ?>" class="btn btn-primary btn-sm mt-3">
                                <i class="bi bi-plus-circle me-2"></i>Add First Enrollment
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo $__env->make('admin.partials.pagination', ['paginator' => $enrollments, 'label' => 'enrollments'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
    .table td {
        vertical-align: middle;
    }
    @media print {
        .page-header, .btn, .pagination, .card-body form {
            display: none !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/enrollments/index.blade.php ENDPATH**/ ?>