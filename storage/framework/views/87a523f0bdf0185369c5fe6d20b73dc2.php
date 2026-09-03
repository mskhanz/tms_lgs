<?php $__env->startSection('title', 'Quiz Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="bi bi-clipboard-check me-2"></i>Quiz Management</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Quizzes</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?php echo e(route('admin.quizzes.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Create Quiz
    </a>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search quizzes..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 quiz-table">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Assigned to</th>
                        <th>Questions</th>
                        <th>Duration</th>
                        <th>%</th>
                        <th>Attempts</th>
                        <th>Status</th>
                        <th>Result</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($quiz->title); ?></strong>
                            <?php if($quiz->description): ?>
                            <br><small class="text-muted"><?php echo e(Str::limit($quiz->description, 60)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($quiz->assign_to): ?>
                                <span class="badge bg-<?php echo e($quiz->assign_to === 'batch' ? 'primary' : 'info'); ?>"><?php echo e($quiz->assign_to === 'batch' ? 'Batch' : 'Program'); ?></span>
                                <div class="small text-muted"><?php echo e($quiz->assignmentLabel()); ?></div>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Not assigned</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-info"><?php echo e($quiz->questions_count); ?></span></td>
                        <td><?php echo e($quiz->duration_minutes ? $quiz->duration_minutes.' min' : 'No limit'); ?></td>
                        <td><?php echo e($quiz->passing_percentage); ?>%</td>
                        <td><?php echo e($quiz->attempts_count); ?></td>
                        <td>
                            <?php if($quiz->is_active): ?>
                            <span class="badge bg-success">Active</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.quizzes.results', $quiz)); ?>" class="btn btn-sm btn-outline-success quiz-action-btn" title="View results">
                                <i class="bi bi-bar-chart"></i>
                                Results
                                <?php if($quiz->completed_attempts_count): ?>
                                <span class="badge bg-success ms-1"><?php echo e($quiz->completed_attempts_count); ?></span>
                                <?php endif; ?>
                            </a>
                        </td>
                        <td class="text-end text-nowrap">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="<?php echo e(route('admin.quizzes.show', $quiz)); ?>" class="btn btn-outline-primary quiz-action-btn" title="Manage"><i class="bi bi-eye"></i></a>
                                <a href="<?php echo e(route('admin.quizzes.edit', $quiz)); ?>" class="btn btn-outline-secondary quiz-action-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('admin.quizzes.toggle-status', $quiz)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-outline-warning quiz-action-btn" title="Toggle Status"><i class="bi bi-toggle-on"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="9" class="text-center py-4 text-muted">No quizzes found. Create one or run the seeder.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('admin.partials.pagination', ['paginator' => $quizzes, 'label' => 'quizzes'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .quiz-table td { vertical-align: middle; }
    .quiz-action-btn {
        --bs-btn-padding-y: 0.15rem;
        --bs-btn-padding-x: 0.4rem;
        --bs-btn-font-size: 0.75rem;
        --bs-btn-border-radius: 0.3rem;
        line-height: 1.2;
        min-width: 1.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }
    .quiz-action-btn .badge {
        font-size: 0.65rem;
        padding: 0.15em 0.4em;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/index.blade.php ENDPATH**/ ?>