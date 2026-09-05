<?php
    $statusLabels = [
        'present' => 'Present',
        'absent' => 'Absent',
        'late' => 'Late',
        'excused' => 'Excused',
        'not_marked' => 'Not marked',
    ];
    $statusBadges = [
        'present' => 'success',
        'absent' => 'danger',
        'late' => 'warning',
        'excused' => 'info',
        'not_marked' => 'secondary',
    ];
    $compact = $compact ?? false;
?>

<div class="card mb-4 trainee-enrollments-card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="bi bi-journal-text me-2"></i>
            Enrollments (<?php echo e($enrollmentSummaries->count()); ?>)
        </h5>
        <?php if($attendanceOverview['totalSessions'] > 0): ?>
        <span class="badge bg-success">
            Overall attendance: <?php echo e(number_format($attendanceOverview['overallPercentage'], 1)); ?>%
        </span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php $__empty_1 = true; $__currentLoopData = $enrollmentSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $enrollment = $summary->enrollment;
                $batch = $summary->batch;
                $program = $summary->program;
                $minRequired = $batch?->effectiveMinAttendancePercentage();
            ?>
            <div class="enrollment-block <?php echo e(! $loop->last ? 'mb-4 pb-4 border-bottom' : ''); ?>">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h6 class="mb-1 fw-semibold"><?php echo e($program->title ?? 'Training not set'); ?></h6>
                        <div class="small text-muted">
                            Batch <strong class="text-body"><?php echo e($batch->batch_code ?? 'N/A'); ?></strong>
                            <?php if($program?->code): ?>
                                <span class="ms-1">(<?php echo e($program->code); ?>)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="badge enrollment-status-badge
                        <?php echo e($enrollment->status === 'completed' ? 'bg-success' : ''); ?>

                        <?php echo e($enrollment->status === 'in_progress' ? 'bg-primary' : ''); ?>

                        <?php echo e($enrollment->status === 'enrolled' ? 'bg-warning text-dark' : ''); ?>

                        <?php echo e(in_array($enrollment->status, ['dropped', 'failed']) ? 'bg-danger' : ''); ?>">
                        <?php echo e(ucwords(str_replace('_', ' ', $enrollment->status))); ?>

                    </span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Enrolled on</div>
                        <div class="fw-semibold"><?php echo e($enrollment->enrollment_date?->format('d M, Y') ?? 'N/A'); ?></div>
                    </div>
                    <?php if($batch?->start_date && $batch?->end_date): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Training dates</div>
                        <div class="fw-semibold">
                            <?php echo e($batch->start_date->format('d M, Y')); ?> – <?php echo e($batch->end_date->format('d M, Y')); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Batch status</div>
                        <?php if($batch): ?>
                        <span class="badge bg-<?php echo e($batch->statusBadge()); ?>"><?php echo e($batch->statusLabel()); ?></span>
                        <?php else: ?>
                        <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Venue</div>
                        <div class="fw-semibold"><?php echo e($batch->venue ?? 'N/A'); ?></div>
                    </div>
                    <?php if($enrollment->enrolledBy): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Enrolled by</div>
                        <div class="fw-semibold"><?php echo e($enrollment->enrolledBy->name); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($enrollment->completion_date): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Completion date</div>
                        <div class="fw-semibold"><?php echo e($enrollment->completion_date->format('d M, Y')); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($enrollment->assessment_score !== null): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="text-muted small">Assessment score</div>
                        <div class="fw-semibold"><?php echo e(number_format((float) $enrollment->assessment_score, 1)); ?>%</div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if($summary->showAttendance): ?>
                <div class="attendance-panel border rounded p-3 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <div class="fw-semibold"><i class="bi bi-calendar-check me-1"></i>Attendance</div>
                            <div class="small text-muted">
                                <?php echo e(number_format((float) $enrollment->attendance_percentage, 1)); ?>%
                                <?php if($minRequired !== null): ?>
                                    · Required <?php echo e($minRequired); ?>%
                                    <?php if((float) $enrollment->attendance_percentage >= $minRequired): ?>
                                        <span class="badge bg-success ms-1">Met</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger ms-1">Below required</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if($batch && ! $compact): ?>
                        <a href="<?php echo e(route('admin.batches.attendance.show', $batch->id)); ?>" class="btn btn-sm btn-outline-primary no-print">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Batch attendance
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-success">Present: <?php echo e($summary->statusCounts['present']); ?></span>
                        <span class="badge bg-danger">Absent: <?php echo e($summary->statusCounts['absent']); ?></span>
                        <span class="badge bg-warning text-dark">Late: <?php echo e($summary->statusCounts['late']); ?></span>
                        <span class="badge bg-info">Excused: <?php echo e($summary->statusCounts['excused']); ?></span>
                        <span class="badge bg-secondary">Not marked: <?php echo e($summary->statusCounts['not_marked']); ?></span>
                    </div>

                    <?php if($summary->sessionRows->count()): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0 bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Session</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Check-in</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $summary->sessionRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <?php echo e($row->session->title); ?>

                                        <?php if($row->session->sessionType): ?>
                                            <span class="text-muted small">(<?php echo e($row->session->sessionType->name); ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($row->session->session_date?->format('d M Y')); ?></td>
                                    <td>
                                        <?php if($row->session->start_time && $row->session->end_time): ?>
                                            <?php echo e(\Carbon\Carbon::parse($row->session->start_time)->format('h:i A')); ?>

                                            –
                                            <?php echo e(\Carbon\Carbon::parse($row->session->end_time)->format('h:i A')); ?>

                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($statusBadges[$row->status] ?? 'secondary'); ?>">
                                            <?php echo e($statusLabels[$row->status] ?? ucfirst($row->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($row->record?->check_in_time?->format('h:i A') ?? '—'); ?></td>
                                    <td><?php echo e($row->record?->remarks ?? '—'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted small mb-0">No sessions scheduled for this batch yet.</p>
                    <?php endif; ?>
                </div>
                <?php elseif($batch && $batch->isAttendanceEnabled()): ?>
                <div class="alert alert-light border small mb-0 py-2">
                    <i class="bi bi-info-circle me-1"></i>Attendance is enabled but no sessions have been marked yet.
                </div>
                <?php endif; ?>

                <?php if($enrollment->remarks): ?>
                <div class="mt-3 small">
                    <span class="text-muted">Enrollment remarks:</span> <?php echo e($enrollment->remarks); ?>

                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted text-center mb-0 py-3">No enrollments yet.</p>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/trainees/partials/enrollment-attendance.blade.php ENDPATH**/ ?>