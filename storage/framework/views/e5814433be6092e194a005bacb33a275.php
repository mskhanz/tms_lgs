<?php if($assignment->due_at): ?>
<span class="value"><?php echo e($assignment->due_at->format('d M Y, h:i A')); ?></span>
<span class="asg-countdown"
      data-asg-due="<?php echo e($assignment->due_at->toIso8601String()); ?>"
      title="Time remaining until due date">—</span>
<?php else: ?>
<span class="value">No due date</span>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/assignments/_due-countdown.blade.php ENDPATH**/ ?>