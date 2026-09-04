<?php
    $submission = $submissions->get($assignment->id);
    $status = $assignment->traineeStatus();
    $isSubmitted = $submission?->isSubmitted();
    if ($isSubmitted) {
        $badgeLabel = $submission->isLate() ? 'Late' : 'Submitted';
        $badgeClass = $submission->isLate() ? 'bg-warning text-dark' : 'bg-success text-white';
    } elseif ($submission) {
        $badgeLabel = 'Draft';
        $badgeClass = 'bg-info text-white';
    } else {
        $badgeLabel = match ($status) {
            'open' => 'Not submitted',
            'scheduled' => $assignment->traineeStatusLabel(),
            'closed' => 'Closed',
            default => 'Inactive',
        };
        $badgeClass = match ($status) {
            'open' => 'bg-secondary text-white',
            'scheduled' => 'bg-warning text-dark',
            default => 'bg-light text-dark',
        };
    }
?>

<div class="<?php echo e($cardClass ?? 'card h-100'); ?>">
    <div class="<?php echo e(isset($cardClass) ? 'p-3 d-flex flex-column h-100' : 'card-body d-flex flex-column'); ?>">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
            <h5 class="<?php echo e(isset($cardClass) ? 'h6 fw-semibold' : 'card-title'); ?> mb-0"><?php echo e($assignment->title); ?></h5>
            <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($badgeLabel); ?></span>
        </div>

        <?php if($assignment->instructions): ?>
        <p class="card-text text-muted small flex-grow-1"><?php echo e(Str::limit(strip_tags($assignment->instructions), 100)); ?></p>
        <?php endif; ?>

        <ul class="list-unstyled small mb-3">
            <li><i class="bi bi-mortarboard me-1"></i><?php echo e($assignment->assignmentLabel()); ?></li>
            <li><i class="bi bi-trophy me-1"></i>Marks: <?php echo e(number_format((float) $assignment->total_marks, 0)); ?></li>
            <li><i class="bi bi-paperclip me-1"></i><?php echo e($assignment->attachments_count ?? $assignment->attachments->count()); ?> file(s)</li>
            <li>
                <i class="bi bi-calendar-event me-1"></i>
                Due: <?php echo e($assignment->due_at?->format('d M Y, h:i A') ?? 'No due date'); ?>

                <?php if(! $isSubmitted && $assignment->due_at): ?>
                    <span class="asg-countdown"
                          data-asg-due="<?php echo e($assignment->due_at->toIso8601String()); ?>"
                          title="Time remaining until due date">—</span>
                <?php endif; ?>
            </li>
        </ul>

        <?php if($status === 'open' || $submission): ?>
        <a href="<?php echo e(route('trainee.assignments.show', $assignment)); ?>" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i>
            <?php echo e($isSubmitted ? 'View / Update' : ($submission ? 'Continue' : 'Open Assignment')); ?>

        </a>
        <?php elseif($status === 'scheduled'): ?>
        <button class="btn btn-outline-warning w-100" disabled>Not open yet</button>
        <?php else: ?>
        <button class="btn btn-secondary w-100" disabled>Unavailable</button>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/trainee/assignments/_card.blade.php ENDPATH**/ ?>