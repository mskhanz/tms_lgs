

<?php $__env->startSection('title', 'Roles & Permissions'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-shield-check me-2"></i>Roles & Permissions</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Roles & Permissions</li>
        </ol>
    </nav>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?php echo e(route('admin.roles.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Role
        </a>
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

<!-- Roles Grid -->
<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 role-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h5 class="mb-0 text-primary"><?php echo e($role->display_name); ?></h5>
                <?php if(in_array($role->name, ['system_admin', 'director', 'deputy_director'])): ?>
                    <span class="badge bg-danger">System Role</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-muted small">Identifier:</strong>
                    <code class="ms-2"><?php echo e($role->name); ?></code>
                </div>
                
                <?php if($role->description): ?>
                <p class="text-muted small mb-3"><?php echo e($role->description); ?></p>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small"><i class="bi bi-people me-1"></i>Users:</span>
                    <span class="fw-semibold text-primary"><?php echo e($role->users_count); ?></span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small"><i class="bi bi-key me-1"></i>Permissions:</span>
                    <span class="fw-semibold text-success"><?php echo e($role->permissions_count); ?></span>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="btn-group w-100">
                    <a href="<?php echo e(route('admin.roles.show', $role->id)); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>View
                    </a>
                    <a href="<?php echo e(route('admin.roles.edit', $role->id)); ?>" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <?php if(!in_array($role->name, ['system_admin', 'director', 'deputy_director'])): ?>
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger" 
                            onclick="confirmDelete(<?php echo e($role->id); ?>)">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                    <?php endif; ?>
                </div>

                <form id="delete-form-<?php echo e($role->id); ?>" 
                      action="<?php echo e(route('admin.roles.destroy', $role->id)); ?>" 
                      method="POST" 
                      class="d-none">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-shield-x" style="font-size: 4rem; color: #ccc;"></i>
            <p class="text-muted mt-3 mb-0">No roles found</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Permissions Reference -->
<div class="card mt-5">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Available Permissions</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupPermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <h6 class="text-primary mb-3">
                    <i class="bi bi-folder me-2"></i><?php echo e(ucwords(str_replace('_', ' ', $group))); ?>

                </h6>
                <ul class="list-unstyled ps-3">
                    <?php $__currentLoopData = $groupPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="mb-2">
                        <i class="bi bi-key-fill text-success me-2"></i>
                        <span class="small"><?php echo e($permission->display_name); ?></span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(roleId) {
    if (confirm('Are you sure you want to delete this role? Users with this role will lose associated permissions.')) {
        document.getElementById('delete-form-' + roleId).submit();
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .role-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: #10b981;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>