<?php if (! $__env->hasRenderedOnce('40733d77-5a0a-4a06-981d-fd8434f355a6')): $__env->markAsRenderedOnce('40733d77-5a0a-4a06-981d-fd8434f355a6'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    function formatRemaining(ms) {
        if (ms <= 0) {
            return 'Overdue';
        }

        var totalSeconds = Math.floor(ms / 1000);
        var days = Math.floor(totalSeconds / 86400);
        var hours = Math.floor((totalSeconds % 86400) / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);
        var seconds = totalSeconds % 60;

        return days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's left';
    }

    function tick() {
        document.querySelectorAll('[data-asg-due]').forEach(function (el) {
            var due = Date.parse(el.getAttribute('data-asg-due'));
            if (Number.isNaN(due)) {
                el.textContent = '';
                return;
            }
            var remaining = due - Date.now();
            el.textContent = formatRemaining(remaining);
            el.classList.toggle('asg-countdown-overdue', remaining <= 0);
        });
    }

    tick();
    setInterval(tick, 1000);
})();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/assignments/_due-countdown-script.blade.php ENDPATH**/ ?>