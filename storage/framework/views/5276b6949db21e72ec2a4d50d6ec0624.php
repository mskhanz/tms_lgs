
<?php if (! $__env->hasRenderedOnce('90786ca9-a750-4339-99ca-6d638be2a627')): $__env->markAsRenderedOnce('90786ca9-a750-4339-99ca-6d638be2a627'); ?>
<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js" referrerpolicy="origin"></script>
<script>
(function () {
    if (typeof tinymce === 'undefined') return;

    tinymce.init({
        selector: 'textarea.asg-richtext',
        base_url: 'https://cdn.jsdelivr.net/npm/tinymce@7.6.0',
        suffix: '.min',
        height: 320,
        menubar: false,
        branding: false,
        promotion: false,
        plugins: 'lists link autolink',
        toolbar: 'undo redo | styles | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link | removeformat',
        style_formats: [
            { title: 'Paragraph', format: 'p' },
            { title: 'Heading 2', format: 'h2' },
            { title: 'Heading 3', format: 'h3' },
            { title: 'Heading 4', format: 'h4' }
        ],
        content_style: 'body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; font-size: 14px; line-height: 1.6; }',
        convert_urls: false,
        relative_urls: false,
        setup: function (editor) {
            editor.on('change input undo redo', function () {
                editor.save();
            });
        }
    });

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            if (window.tinymce) {
                tinymce.triggerSave();
            }
        });
    });
})();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/assignments/_richtext.blade.php ENDPATH**/ ?>