<?php
    $userAttempts = $attempts->get($quiz->id, collect());
    $completed = $userAttempts->where('status', 'completed')->count();
    $inProgress = $userAttempts->firstWhere('status', 'in_progress');
    $best = $userAttempts->where('status', 'completed')->max('percentage');
    $canTake = $completed < $quiz->max_attempts;
    $status = $quiz->traineeStatus();
    $statusClass = match ($status) {
        'open' => 'bg-success',
        'scheduled' => 'bg-warning text-dark',
        'closed' => 'bg-secondary',
        default => 'bg-light text-dark',
    };
?>

<div class="<?php echo e($cardClass ?? 'card h-100'); ?>">
    <div class="<?php echo e(isset($cardClass) ? 'p-3 d-flex flex-column h-100' : 'card-body d-flex flex-column'); ?>">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
            <h5 class="<?php echo e(isset($cardClass) ? 'h6 fw-semibold' : 'card-title'); ?> mb-0"><?php echo e($quiz->title); ?></h5>
            <span class="badge <?php echo e($statusClass); ?>"><?php echo e($quiz->traineeStatusLabel()); ?></span>
        </div>

        <?php if($quiz->description): ?>
        <p class="card-text text-muted small flex-grow-1"><?php echo e(Str::limit($quiz->description, 100)); ?></p>
        <?php endif; ?>

        <ul class="list-unstyled small mb-3">
            <li><i class="bi bi-mortarboard me-1"></i><?php echo e($quiz->assignmentLabel()); ?></li>
            <li><i class="bi bi-question-circle me-1"></i><?php echo e($quiz->questions_count); ?> Questions</li>
            <li><i class="bi bi-clock me-1"></i><?php echo e($quiz->duration_minutes ? $quiz->duration_minutes.' minutes' : 'No time limit'); ?></li>
            <li><i class="bi bi-trophy me-1"></i>Pass: <?php echo e($quiz->passing_percentage); ?>%</li>
            <li><i class="bi bi-arrow-repeat me-1"></i>Attempts: <?php echo e($completed); ?>/<?php echo e($quiz->max_attempts); ?></li>
            <?php if($quiz->available_from || $quiz->available_until): ?>
            <li><i class="bi bi-calendar-range me-1"></i>
                <?php if($quiz->available_from && $quiz->available_until): ?>
                    <?php echo e($quiz->available_from->format('M d, h:i A')); ?> - <?php echo e($quiz->available_until->format('M d, h:i A')); ?>

                <?php elseif($quiz->available_from): ?>
                    From <?php echo e($quiz->available_from->format('M d, h:i A')); ?>

                <?php else: ?>
                    Until <?php echo e($quiz->available_until->format('M d, h:i A')); ?>

                <?php endif; ?>
            </li>
            <?php endif; ?>
            <?php if($best !== null): ?>
            <li><i class="bi bi-star me-1"></i>Best: <?php echo e($best); ?>%</li>
            <?php endif; ?>
        </ul>

        <?php if($inProgress && $status === 'open'): ?>
        <a href="<?php echo e(route('trainee.quizzes.take', $inProgress)); ?>" class="btn btn-warning w-100">
            <i class="bi bi-play-fill me-1"></i>Continue Quiz
        </a>
        <?php elseif($canTake && $status === 'open'): ?>
        <a href="<?php echo e(route('trainee.quizzes.start', $quiz)); ?>" class="btn btn-primary w-100">
            <i class="bi bi-play-circle me-1"></i><?php echo e($completed > 0 ? 'Retake Quiz' : 'Start Quiz'); ?>

        </a>
        <?php elseif($status === 'scheduled'): ?>
        <button class="btn btn-outline-warning w-100" disabled>Not open yet</button>
        <?php elseif($status === 'closed'): ?>
        <?php $last = $userAttempts->where('status', 'completed')->first(); ?>
        <?php if($last): ?>
        <a href="<?php echo e(route('trainee.quizzes.result', $last)); ?>" class="btn btn-outline-success w-100">View Result</a>
        <?php else: ?>
        <button class="btn btn-secondary w-100" disabled>Quiz closed</button>
        <?php endif; ?>
        <?php elseif(! $canTake): ?>
        <?php $last = $userAttempts->where('status', 'completed')->first(); ?>
        <?php if($last): ?>
        <a href="<?php echo e(route('trainee.quizzes.result', $last)); ?>" class="btn btn-outline-success w-100">View Result</a>
        <?php else: ?>
        <button class="btn btn-secondary w-100" disabled>No attempts left</button>
        <?php endif; ?>
        <?php else: ?>
        <button class="btn btn-secondary w-100" disabled>Unavailable</button>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/trainee/quizzes/_card.blade.php ENDPATH**/ ?>