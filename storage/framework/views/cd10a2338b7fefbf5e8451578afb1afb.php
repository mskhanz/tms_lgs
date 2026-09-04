<?php $__env->startSection('title', 'Attendance Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-calendar-check me-2"></i>Attendance Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

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

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Search program</label>
                <input type="text" name="search" class="form-control" value="<?php echo e(request('search')); ?>" placeholder="Program title or code...">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Session report date</label>
                <input type="date" name="report_date" class="form-control" value="<?php echo e($reportDate); ?>">
            </div>
            <input type="hidden" name="tab" value="<?php echo e(request('tab', 'manage')); ?>">
            <div class="col-md-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Apply</button>
                <a href="<?php echo e(route('admin.attendance.index')); ?>" class="btn btn-outline-secondary">Reset</a>
                <a href="<?php echo e(route('admin.attendance.session-report', ['date' => $reportDate])); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-1"></i>Print report
                </a>
            </div>
        </form>
    </div>
</div>

<ul class="nav nav-tabs mb-4" id="attendanceTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo e(request('tab', 'manage') === 'manage' ? 'active' : ''); ?>"
                id="manage-tab"
                data-bs-toggle="tab"
                data-bs-target="#manage-panel"
                type="button"
                role="tab">
            <i class="bi bi-sliders me-1"></i>Batch Management
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo e(request('tab') === 'sessions' ? 'active' : ''); ?>"
                id="sessions-tab"
                data-bs-toggle="tab"
                data-bs-target="#sessions-panel"
                type="button"
                role="tab">
            <i class="bi bi-calendar-event me-1"></i>Sessions (<?php echo e($reportDate); ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo e(request('tab') === 'report' ? 'active' : ''); ?>"
                id="report-tab"
                data-bs-toggle="tab"
                data-bs-target="#report-panel"
                type="button"
                role="tab">
            <i class="bi bi-clipboard-data me-1"></i>Session Report (<?php echo e(\Carbon\Carbon::parse($reportDate)->format('d M Y')); ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="attendanceTabContent">
    <div class="tab-pane fade <?php echo e(request('tab', 'manage') === 'manage' ? 'show active' : ''); ?>" id="manage-panel" role="tabpanel">
<?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-0"><?php echo e($program->title); ?></h5>
            <small class="text-muted"><?php echo e($program->code); ?></small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-<?php echo e($program->isAttendanceEnabled() ? 'success' : 'secondary'); ?>">
                Program: <?php echo e($program->isAttendanceEnabled() ? 'Active' : 'Inactive'); ?>

            </span>
            <form method="POST" action="<?php echo e(route('admin.programs.attendance.toggle', $program)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-sm btn-<?php echo e($program->isAttendanceEnabled() ? 'outline-danger' : 'outline-success'); ?>">
                    <?php echo e($program->isAttendanceEnabled() ? 'Disable' : 'Enable'); ?>

                </button>
            </form>
            <?php if($program->isAttendanceEnabled() && $program->batches->count()): ?>
            <form method="POST" action="<?php echo e(route('admin.programs.attendance.activate-batches', $program)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-sm btn-primary">
                    Activate all batches
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if($program->batches->count()): ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Dates</th>
                        <th>Enrollments</th>
                        <th>Sessions</th>
                        <th>Attendance</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $program->batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="fw-semibold">
                            <a href="<?php echo e(route('admin.batches.show', $batch)); ?>"><?php echo e($batch->batch_code); ?></a>
                        </td>
                        <td><?php echo e($batch->start_date?->format('d M Y')); ?> – <?php echo e($batch->end_date?->format('d M Y')); ?></td>
                        <td><?php echo e($batch->enrollments_count); ?></td>
                        <td><?php echo e($batch->sessions_count); ?></td>
                        <td>
                            <?php if($batch->isAttendanceActive()): ?>
                                <span class="badge bg-success">Active</span>
                            <?php elseif($batch->attendance_enabled && $program->isAttendanceEnabled()): ?>
                                <span class="badge bg-warning text-dark">Enabled</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <form method="POST" action="<?php echo e(route('admin.batches.attendance.toggle', $batch)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-<?php echo e($batch->attendance_enabled ? 'danger' : 'success'); ?>"
                                            <?php echo e(! $program->isAttendanceEnabled() ? 'disabled' : ''); ?>>
                                        <?php echo e($batch->attendance_enabled ? 'Disable' : 'Enable'); ?>

                                    </button>
                                </form>
                                <?php if($batch->isAttendanceActive()): ?>
                                <a href="<?php echo e(route('admin.batches.attendance.show', $batch)); ?>" class="btn btn-sm btn-primary">
                                    Manage
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted text-center py-4 mb-0">No batches for this program.</p>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="card">
    <div class="card-body text-center text-muted py-5">
        No training programs found.
    </div>
</div>
<?php endif; ?>
    </div>

    <div class="tab-pane fade <?php echo e(request('tab') === 'sessions' ? 'show active' : ''); ?>" id="sessions-panel" role="tabpanel">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Sessions on <?php echo e(\Carbon\Carbon::parse($reportDate)->format('d M Y')); ?></h5>
            </div>
            <div class="card-body p-0">
                <?php if($sessionsForDate->count()): ?>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Program</th>
                                <th>Batch</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Marked</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $sessionsForDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sessionData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fw-semibold"><?php echo e($sessionData->session->title); ?></td>
                                <td><?php echo e($sessionData->program->title ?? 'N/A'); ?></td>
                                <td><?php echo e($sessionData->batch->batch_code ?? 'N/A'); ?></td>
                                <td>
                                    <?php echo e(\Carbon\Carbon::parse($sessionData->session->start_time)->format('h:i A')); ?>

                                    –
                                    <?php echo e(\Carbon\Carbon::parse($sessionData->session->end_time)->format('h:i A')); ?>

                                </td>
                                <td><?php echo e($sessionData->session->venue ?? $sessionData->batch->venue ?? 'N/A'); ?></td>
                                <td>
                                    <?php echo e($sessionData->totalEnrolled - $sessionData->statusCounts['not_marked']); ?>

                                    / <?php echo e($sessionData->totalEnrolled); ?>

                                </td>
                                <td class="text-end">
                                    <?php if($sessionData->batch?->isAttendanceActive()): ?>
                                    <a href="<?php echo e(route('admin.batches.attendance.sessions.mark', [$sessionData->batch, $sessionData->session])); ?>" class="btn btn-sm btn-primary">
                                        Mark
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center py-4 mb-0">No sessions scheduled for this date.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade <?php echo e(request('tab') === 'report' ? 'show active' : ''); ?>" id="report-panel" role="tabpanel">
        <?php if($sessionsForDate->count()): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted mb-0">
                    Attendance report for <strong><?php echo e($sessionsForDate->count()); ?></strong> session(s) on <?php echo e(\Carbon\Carbon::parse($reportDate)->format('d M Y')); ?>.
                </p>
                <a href="<?php echo e(route('admin.attendance.session-report', ['date' => $reportDate])); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-printer me-1"></i>Print full report
                </a>
            </div>
            <?php $__currentLoopData = $sessionsForDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sessionData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('admin.attendance.partials.session-report-card', ['sessionData' => $sessionData], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                No sessions found for <?php echo e(\Carbon\Carbon::parse($reportDate)->format('d M Y')); ?>.
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('#attendanceTabs .nav-link').forEach(function (tab) {
    tab.addEventListener('shown.bs.tab', function (event) {
        const tabId = event.target.id.replace('-tab', '');
        const map = { manage: 'manage', sessions: 'sessions', report: 'report' };
        const value = map[tabId] || 'manage';
        const url = new URL(window.location.href);
        url.searchParams.set('tab', value);
        window.history.replaceState({}, '', url);
        const tabInput = document.querySelector('input[name="tab"]');
        if (tabInput) {
            tabInput.value = value;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/attendance/index.blade.php ENDPATH**/ ?>