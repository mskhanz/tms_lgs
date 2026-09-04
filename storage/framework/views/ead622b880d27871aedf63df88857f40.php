<?php
    $traineeName = $traineeName ?? function ($user) {
        return $user?->traineeProfile?->emp_name ?: ($user->name ?? '—');
    };
    $traineeOrg = $traineeOrg ?? function ($user) {
        return $user?->traineeProfile?->organization?->name ?: '—';
    };
?>

<div class="banner">
    <table>
        <tr>
            <td style="width: 52px; vertical-align: middle;">
                <?php if(!empty($logoSrc)): ?>
                    <img src="<?php echo e($logoSrc); ?>" class="logo" alt="Logo">
                <?php elseif(!empty($logoUrl)): ?>
                    <img src="<?php echo e($logoUrl); ?>" class="logo" alt="Logo">
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
            <td><?php echo e($traineeName($attempt->user)); ?></td>
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
            <th style="width: 110px;">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $notAttempted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trainee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($index + 1); ?></td>
            <td><?php echo e($traineeName($trainee)); ?></td>
            <td><?php echo e($traineeOrg($trainee)); ?></td>
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
            <td colspan="4" class="empty">
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
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/_results-document.blade.php ENDPATH**/ ?>