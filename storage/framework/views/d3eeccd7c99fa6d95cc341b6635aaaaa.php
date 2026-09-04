
<style>
    .asg-workspace { margin-bottom: 1.5rem; }
    .asg-hero {
        background: linear-gradient(135deg, #047857 0%, #059669 48%, #10b981 100%);
        color: #fff;
        border-radius: 16px;
        padding: 1.35rem 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 10px 28px rgba(5, 150, 105, 0.2);
        position: relative;
        overflow: hidden;
    }
    .asg-hero::after {
        content: '';
        position: absolute;
        top: -35%;
        right: -8%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        pointer-events: none;
    }
    .asg-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    .asg-hero-identity {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        min-width: 0;
    }
    .asg-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.45);
        background: rgba(255,255,255,0.16);
        flex-shrink: 0;
    }
    .asg-avatar-fallback {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.4rem;
        color: #fff;
    }
    .asg-hero h1 {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0;
        color: #fff;
        line-height: 1.3;
    }
    .asg-hero .breadcrumb {
        margin: 0.35rem 0 0;
        --bs-breadcrumb-divider-color: rgba(255,255,255,0.45);
    }
    .asg-hero .breadcrumb-item,
    .asg-hero .breadcrumb-item a {
        color: rgba(255,255,255,0.85);
        font-size: 0.82rem;
    }
    .asg-hero .breadcrumb-item.active { color: #fff; }
    .asg-hero-actions .btn {
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.84rem;
    }
    .asg-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 0.9rem;
        position: relative;
        z-index: 1;
    }
    .asg-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.7rem;
        border-radius: 999px;
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.22);
        font-size: 0.78rem;
        font-weight: 600;
    }
    .asg-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }
    .asg-stat {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 14px;
        padding: 0.95rem 1rem;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        text-align: center;
    }
    .asg-stat b {
        display: block;
        font-size: 1.15rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .asg-stat .badge.bg-success,
    .asg-stat .badge.bg-secondary,
    .asg-stat .badge.bg-info {
        color: #fff !important;
    }
    .asg-trainee-row {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
    }
    .asg-trainee-photo {
        flex-shrink: 0;
        margin: 0;
    }
    .asg-trainee-photo .asg-avatar {
        width: 72px;
        height: 72px;
        border: 3px solid #d1fae5;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.12);
    }
    .asg-trainee-photo .asg-avatar-fallback {
        background: #ecfdf5;
        color: #047857;
        font-size: 1.2rem;
    }
    .asg-trainee-row .asg-meta-list {
        flex: 1;
        min-width: 0;
    }
    .asg-trainee-row .asg-meta-list li {
        padding: 0.2rem 0;
        gap: 0.05rem;
    }
    .asg-trainee-row .asg-meta-list li:first-child { padding-top: 0; }
    .asg-trainee-row .asg-meta-list li:last-child { padding-bottom: 0; margin-bottom: 0; }
    .asg-trainee-row .asg-meta-list .label {
        font-size: 0.72rem;
        line-height: 1.2;
    }
    .asg-trainee-row .asg-meta-list .value {
        font-size: 0.88rem;
        line-height: 1.3;
    }
    .asg-panel {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .asg-panel.asg-panel-auto {
        height: auto;
    }
    .asg-panel.asg-panel-auto .asg-panel-body {
        flex: none;
        padding: 0.85rem 1.1rem;
    }
    .asg-panel:has(.asg-trainee-row) .asg-panel-body {
        padding-bottom: 0.85rem;
    }
    .asg-stat span {
        display: block;
        margin-top: 0.2rem;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
    }
    .asg-panel-head {
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid #eef2f6;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .asg-panel-head h2 {
        margin: 0;
        font-size: 0.92rem;
        font-weight: 700;
        color: #047857;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .asg-panel-body {
        padding: 1.1rem;
        flex: 1;
    }
    .asg-prose {
        white-space: pre-wrap;
        color: #1e293b;
        line-height: 1.65;
        font-size: 0.95rem;
        margin: 0;
    }
    .asg-meta-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .asg-meta-list li {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.55rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }
    .asg-meta-list li:last-child { border-bottom: 0; padding-bottom: 0; }
    .asg-meta-list .label { color: #64748b; }
    .asg-meta-list .value { font-weight: 600; color: #0f172a; text-align: right; }
    .asg-meta-list-left li {
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
        gap: 0.2rem;
    }
    .asg-meta-list-left li:first-child { padding-top: 0; }
    .asg-meta-list-left .label,
    .asg-meta-list-left .value {
        text-align: left;
        width: 100%;
        display: block;
    }
    .asg-meta-list-left .asg-meta-pair {
        flex-direction: row;
        align-items: flex-start;
        gap: 1.5rem;
        width: 100%;
    }
    .asg-meta-list-left .asg-meta-pair > div {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    .asg-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.7rem 0.85rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        margin-bottom: 0.55rem;
    }
    .asg-file:last-child { margin-bottom: 0; }
    .asg-file-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #ecfdf5;
        color: #047857;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .asg-file-name {
        font-weight: 600;
        font-size: 0.88rem;
        color: #0f172a;
        word-break: break-word;
    }
    .asg-file-sub {
        font-size: 0.75rem;
        color: #64748b;
        word-break: break-word;
    }
    .asg-feedback {
        border: 1px solid #a7f3d0;
        background: linear-gradient(180deg, #ecfdf5 0%, #f0fdf4 100%);
        border-radius: 12px;
        padding: 0.95rem 1.05rem;
        margin-bottom: 1rem;
    }
    .asg-feedback-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #047857;
        margin-bottom: 0.35rem;
    }
    .asg-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        padding-top: 0.35rem;
    }
    .asg-empty {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
        text-align: center;
        padding: 1rem 0.5rem;
    }
    .asg-file-actions {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        flex-shrink: 0;
    }
    .asg-preview-frame {
        width: 100%;
        height: min(78vh, 820px);
        border: 0;
        background: #f8fafc;
    }
    .asg-preview-image {
        display: block;
        max-width: 100%;
        max-height: min(78vh, 820px);
        margin: 0 auto;
        padding: 1rem;
        object-fit: contain;
    }
    .asg-preview-unavailable {
        text-align: center;
        color: #64748b;
        padding: 3rem 1.5rem;
    }
    @media (max-width: 992px) {
        .asg-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 576px) {
        .asg-stats { grid-template-columns: 1fr; }
        .asg-hero { padding: 1.1rem; }
        .asg-hero h1 { font-size: 1.15rem; }
    }
</style>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/assignments/_workspace-styles.blade.php ENDPATH**/ ?>