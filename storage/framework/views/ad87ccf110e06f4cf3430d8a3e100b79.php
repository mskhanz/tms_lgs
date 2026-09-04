
<div class="modal fade" id="asgFilePreviewModal" tabindex="-1" aria-labelledby="asgFilePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-truncate" id="asgFilePreviewModalLabel">Document preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="asgPreviewLoading" class="text-center text-muted py-5">Loading preview…</div>
                <iframe id="asgPreviewFrame" class="asg-preview-frame d-none" title="Document preview"></iframe>
                <img id="asgPreviewImage" class="asg-preview-image d-none" alt="Document preview">
                <div id="asgPreviewUnavailable" class="asg-preview-unavailable d-none">
                    <i class="bi bi-eye-slash fs-2 d-block mb-2"></i>
                    <p class="mb-3">Preview is not available for this file type.</p>
                    <a id="asgPreviewDownload" href="#" class="btn btn-success btn-sm">
                        <i class="bi bi-download me-1"></i>Download file
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <a id="asgPreviewFooterDownload" href="#" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-download me-1"></i>Download
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const modalEl = document.getElementById('asgFilePreviewModal');
    if (!modalEl) return;

    const titleEl = document.getElementById('asgFilePreviewModalLabel');
    const loadingEl = document.getElementById('asgPreviewLoading');
    const frameEl = document.getElementById('asgPreviewFrame');
    const imageEl = document.getElementById('asgPreviewImage');
    const unavailableEl = document.getElementById('asgPreviewUnavailable');
    const downloadBtn = document.getElementById('asgPreviewDownload');
    const footerDownload = document.getElementById('asgPreviewFooterDownload');

    function hideAll() {
        loadingEl.classList.add('d-none');
        frameEl.classList.add('d-none');
        imageEl.classList.add('d-none');
        unavailableEl.classList.add('d-none');
        frameEl.removeAttribute('src');
        imageEl.removeAttribute('src');
    }

    document.querySelectorAll('[data-asg-preview]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = btn.getAttribute('data-asg-name') || 'Document';
            const kind = btn.getAttribute('data-asg-kind') || 'other';
            const viewUrl = btn.getAttribute('data-asg-view') || '';
            const downloadUrl = btn.getAttribute('data-asg-download') || '#';

            titleEl.textContent = name;
            downloadBtn.href = downloadUrl;
            footerDownload.href = downloadUrl;
            hideAll();

            if (kind === 'pdf' && viewUrl) {
                loadingEl.classList.remove('d-none');
                frameEl.onload = function () {
                    loadingEl.classList.add('d-none');
                    frameEl.classList.remove('d-none');
                };
                frameEl.src = viewUrl;
            } else if (kind === 'image' && viewUrl) {
                loadingEl.classList.remove('d-none');
                imageEl.onload = function () {
                    loadingEl.classList.add('d-none');
                    imageEl.classList.remove('d-none');
                };
                imageEl.onerror = function () {
                    loadingEl.classList.add('d-none');
                    unavailableEl.classList.remove('d-none');
                };
                imageEl.src = viewUrl;
            } else {
                unavailableEl.classList.remove('d-none');
            }

            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });
    });

    modalEl.addEventListener('hidden.bs.modal', hideAll);
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/assignments/_file-preview-modal.blade.php ENDPATH**/ ?>