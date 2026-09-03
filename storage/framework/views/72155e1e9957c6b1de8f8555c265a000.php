<?php
    $paginator = $paginator ?? null;
    $label = $label ?? 'records';
?>

<?php if($paginator && $paginator->total()): ?>
<div class="admin-pagination">
    <div class="text-muted small">
        Showing <?php echo e($paginator->firstItem()); ?> to <?php echo e($paginator->lastItem()); ?> of <?php echo e(number_format($paginator->total())); ?> <?php echo e($label); ?>

    </div>
    <?php if($paginator->hasPages()): ?>
        <div class="admin-pagination-links">
            <?php echo e($paginator->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/partials/pagination.blade.php ENDPATH**/ ?>