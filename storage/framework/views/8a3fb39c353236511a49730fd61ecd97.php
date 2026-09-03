<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quiz Result Report - <?php echo e($quiz->title); ?></title>
    <style>
        @page { margin: 18px 20px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        .banner { background: #047857; color: #fff; padding: 12px 14px; }
        .banner table { width: 100%; }
        .logo { width: 42px; height: 42px; }
        .brand-kicker { font-size: 8px; margin: 0 0 2px; }
        .brand-title { font-size: 15px; font-weight: bold; margin: 0; }
        .brand-sub { font-size: 9px; margin: 2px 0 0; }
        .meta { text-align: right; font-size: 10px; }
        .info { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info td { width: 25%; vertical-align: top; padding: 6px 8px 8px 0; }
        .label { display: block; font-size: 8px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.4px; }
        .value { display: block; font-size: 11px; font-weight: bold; margin-top: 2px; }
        .stats { width: 100%; border-collapse: collapse; margin: 4px 0 10px; }
        .stats td { width: 16.66%; border: 1px solid #d1d5db; background: #f8fafc; text-align: center; padding: 7px 4px; }
        .stats b { display: block; font-size: 13px; }
        .stats span { display: block; font-size: 8px; color: #6b7280; text-transform: uppercase; margin-top: 2px; }
        .pass { color: #047857; }
        .fail { color: #b91c1c; }
        h3 { font-size: 10px; text-transform: uppercase; letter-spacing: 0.7px; color: #047857; border-bottom: 1px solid #a7f3d0; padding-bottom: 4px; margin: 14px 0 4px; }
        .note, .sub { font-size: 9px; color: #6b7280; margin: 0 0 8px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        table.data th, table.data td { border: 1px solid #d1d5db; padding: 5px 6px; text-align: left; font-size: 10px; vertical-align: top; }
        table.data th { background: #f3f4f6; font-size: 9px; text-transform: uppercase; }
        .badge { display: inline-block; padding: 2px 6px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-secondary { background: #f3f4f6; color: #374151; }
        .muted { color: #6b7280; font-size: 9px; }
        .empty { text-align: center; color: #6b7280; padding: 10px; }
        .footer { margin-top: 14px; font-size: 9px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 6px; }
    </style>
</head>
<body>
    <?php
        $traineeName = function ($user) {
            return $user?->traineeProfile?->emp_name ?: ($user->name ?? '—');
        };
        $traineeOrg = function ($user) {
            return $user?->traineeProfile?->organization?->name ?: '—';
        };
        $traineeCnic = function ($user) {
            return $user?->traineeProfile?->cnic_no ?: '—';
        };
    ?>

    <div class="banner">
        <table>
            <tr>
                <td style="width: 52px; vertical-align: middle;">
                    <?php if(!empty($logoSrc)): ?>
                        <img src="<?php echo e($logoSrc); ?>" class="logo" alt="Logo">
                    <?php endif; ?>
                </td>
                <td>
                    <div class="brand-kicker">Government of Khyber Pakhtunkhwa · Local Governance School</div>
                    <div class="brand-title"><?php echo e(config('app.name')); ?></div>
                    <div class="brand-sub"><?php echo e(config('app.tagline')); ?></div>
                </td>
                <td class="meta">
                    <strong>Quiz Result Report</strong><br>
                    <?php echo e(now()->format('d M Y, h:i A')); ?>

                </td>
            </tr>
        </table>
    </div>

    <table class="info">
        <tr>
            <td><span class="label">Quiz</span><span class="value"><?php echo e($quiz->title); ?></span></td>
            <td><span class="label">Assigned to</span><span class="value"><?php echo e($quiz->assignmentLabel()); ?></span></td>
            <td><span class="label">Passing score</span><span class="value"><?php echo e($quiz->passing_percentage); ?>%</span></td>
            <td><span class="label">Duration</span><span class="value"><?php echo e($quiz->duration_minutes ? $quiz->duration_minutes.' min' : 'No limit'); ?></span></td>
        </tr>
    </table>

    <table class="stats">
        <tr>
            <td><b><?php echo e($stats['assigned']); ?></b><span>Assigned</span></td>
            <td><b><?php echo e($stats['attempted']); ?></b><span>Attempted</span></td>
            <td><b><?php echo e($stats['not_attempted']); ?></b><span>Not attempted</span></td>
            <td><b class="pass"><?php echo e($stats['passed']); ?></b><span>Passed</span></td>
            <td><b class="fail"><?php echo e($stats['failed']); ?></b><span>Failed</span></td>
            <td><b><?php echo e($stats['average']); ?>%</b><span>Average</span></td>
        </tr>
    </table>

    <?php if($quiz->max_attempts > 1): ?>
        <p class="note">Where a trainee has more than one attempt, the highest percentage is shown.</p>
    <?php endif; ?>

    <h3>1. Attempted trainees</h3>
    <p class="sub">Ranked by percentage (highest first).</p>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 42px;">S. No</th>
                <th>Trainee</th>
                <th>Organization</th>
                <th style="width: 70px;">Score</th>
                <th style="width: 55px;">%</th>
                <th style="width: 70px;">Result</th>
                <th style="width: 120px;">Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $attempted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td>
                    <?php echo e($traineeName($attempt->user)); ?>

                    <div class="muted"><?php echo e($traineeCnic($attempt->user)); ?></div>
                </td>
                <td><?php echo e($traineeOrg($attempt->user)); ?></td>
                <td><?php echo e($attempt->correct_answers); ?>/<?php echo e($attempt->total_questions); ?></td>
                <td><?php echo e(number_format((float) $attempt->percentage, 1)); ?>%</td>
                <td>
                    <span class="badge <?php echo e($attempt->passed ? 'badge-success' : 'badge-danger'); ?>">
                        <?php echo e($attempt->passed ? 'Passed' : 'Failed'); ?>

                    </span>
                </td>
                <td><?php echo e($attempt->submitted_at?->format('d M Y, h:i A') ?? '—'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" class="empty">No completed attempts yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>2. Not attempted</h3>
    <p class="sub">Assigned trainees who have not submitted this quiz.</p>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 42px;">S. No</th>
                <th>Trainee</th>
                <th>Organization</th>
                <th>CNIC</th>
                <th style="width: 110px;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $notAttempted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trainee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($traineeName($trainee)); ?></td>
                <td><?php echo e($traineeOrg($trainee)); ?></td>
                <td><?php echo e($traineeCnic($trainee)); ?></td>
                <td>
                    <?php if(in_array($trainee->id, $inProgressIds)): ?>
                        <span class="badge badge-warning">In progress</span>
                    <?php else: ?>
                        <span class="badge badge-secondary">Not attempted</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="empty">
                    <?php echo e($stats['assigned'] ? 'All assigned trainees have attempted this quiz.' : 'No assigned trainees found for this quiz.'); ?>

                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <?php echo e(config('app.name')); ?>, Local Governance School, Government of Khyber Pakhtunkhwa
        · Generated <?php echo e(now()->format('d M Y, h:i A')); ?>

    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/results-pdf.blade.php ENDPATH**/ ?>