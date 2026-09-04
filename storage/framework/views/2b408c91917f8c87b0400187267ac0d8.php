<?php $__env->startSection('title', 'Quiz Results'); ?>

<?php $__env->startSection('content'); ?>
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

<div class="page-header no-print">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-bar-chart me-2"></i>Quiz Result Report</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.quizzes.index')); ?>">Quizzes</a></li>
                    <li class="breadcrumb-item active">Results</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo e(route('admin.quizzes.show', $quiz)); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to quiz
            </a>
            <a href="<?php echo e(route('admin.quizzes.results.pdf', $quiz)); ?>" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
            </a>
            <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-printer me-1"></i>Print
            </button>
        </div>
    </div>
</div>


<div class="quiz-report no-print">
    <div class="quiz-report-banner">
        <div class="quiz-report-brand">
            <img src="<?php echo e(asset('images/kp-logo.png')); ?>" alt="KP Logo" class="quiz-report-logo">
            <div>
                <div class="quiz-report-kicker">Government of Khyber Pakhtunkhwa · Local Governance School</div>
                <h2><?php echo e(config('app.name')); ?></h2>
                <p><?php echo e(config('app.tagline')); ?></p>
            </div>
        </div>
        <div class="quiz-report-meta">
            <strong>Quiz Result Report</strong>
            <span><?php echo e(now()->format('d M Y, h:i A')); ?></span>
        </div>
    </div>

    <div class="quiz-report-info">
        <div>
            <span>Quiz</span>
            <strong><?php echo e($quiz->title); ?></strong>
        </div>
        <div>
            <span>Assigned to</span>
            <strong><?php echo e($quiz->assignmentLabel()); ?></strong>
        </div>
        <div>
            <span>Passing score</span>
            <strong><?php echo e($quiz->passing_percentage); ?>%</strong>
        </div>
        <div>
            <span>Duration</span>
            <strong><?php echo e($quiz->duration_minutes ? $quiz->duration_minutes.' min' : 'No limit'); ?></strong>
        </div>
    </div>

    <div class="quiz-report-stats">
        <div><b><?php echo e($stats['assigned']); ?></b><small>Assigned</small></div>
        <div><b><?php echo e($stats['attempted']); ?></b><small>Attempted</small></div>
        <div><b><?php echo e($stats['not_attempted']); ?></b><small>Not attempted</small></div>
        <div class="is-pass"><b><?php echo e($stats['passed']); ?></b><small>Passed</small></div>
        <div class="is-fail"><b><?php echo e($stats['failed']); ?></b><small>Failed</small></div>
        <div><b><?php echo e($stats['average']); ?>%</b><small>Average</small></div>
    </div>

    <?php if($quiz->max_attempts > 1): ?>
    <p class="quiz-report-note">Where a trainee has more than one attempt, the highest percentage is shown.</p>
    <?php endif; ?>

    <h3 class="quiz-report-section">1. Attempted trainees</h3>
    <p class="quiz-report-section-sub">Ranked by percentage (highest first).</p>
    <div class="table-responsive">
        <table class="table quiz-report-table mb-0">
            <thead>
                <tr>
                    <th style="width: 56px;">S. No</th>
                    <th>Trainee</th>
                    <th>CNIC</th>
                    <th>Organization</th>
                    <th>Score</th>
                    <th>%</th>
                    <th>Result</th>
                    <th>Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $attempted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><strong><?php echo e($traineeName($attempt->user)); ?></strong></td>
                    <td><?php echo e($traineeCnic($attempt->user)); ?></td>
                    <td><?php echo e($traineeOrg($attempt->user)); ?></td>
                    <td><?php echo e($attempt->correct_answers); ?>/<?php echo e($attempt->total_questions); ?></td>
                    <td class="fw-semibold"><?php echo e(number_format((float) $attempt->percentage, 1)); ?>%</td>
                    <td>
                        <span class="badge bg-<?php echo e($attempt->passed ? 'success' : 'danger'); ?>">
                            <?php echo e($attempt->passed ? 'Passed' : 'Failed'); ?>

                        </span>
                    </td>
                    <td><?php echo e($attempt->submitted_at?->format('d M Y, h:i A') ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No completed attempts yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <h3 class="quiz-report-section">2. Not attempted</h3>
    <p class="quiz-report-section-sub">Assigned trainees who have not submitted this quiz.</p>
    <div class="table-responsive">
        <table class="table quiz-report-table mb-0">
            <thead>
                <tr>
                    <th style="width: 56px;">S. No</th>
                    <th>Trainee</th>
                    <th>Organization</th>
                    <th>CNIC</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $notAttempted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trainee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><strong><?php echo e($traineeName($trainee)); ?></strong></td>
                    <td><?php echo e($traineeOrg($trainee)); ?></td>
                    <td><?php echo e($traineeCnic($trainee)); ?></td>
                    <td>
                        <?php if(in_array($trainee->id, $inProgressIds)): ?>
                            <span class="badge bg-warning text-dark">In progress</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Not attempted</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <?php echo e($stats['assigned'] ? 'All assigned trainees have attempted this quiz.' : 'No assigned trainees found for this quiz.'); ?>

                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="quiz-report-footer">
        <?php echo e(config('app.name')); ?>, Local Governance School, Government of Khyber Pakhtunkhwa
        · Generated <?php echo e(now()->format('d M Y, h:i A')); ?>

    </div>
</div>


<div class="quiz-print-document print-only">
    <?php echo $__env->make('admin.quizzes._results-document', [
        'logoUrl' => asset('images/kp-logo.png'),
        'traineeName' => $traineeName,
        'traineeOrg' => $traineeOrg,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .quiz-report {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .quiz-report-banner {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: flex-start;
        background: #047857;
        color: #fff;
        padding: 1.1rem 1.25rem;
    }
    .quiz-report-brand {
        display: flex;
        gap: 0.85rem;
        align-items: center;
    }
    .quiz-report-logo {
        width: 48px;
        height: 48px;
        object-fit: contain;
        background: #fff;
        border-radius: 50%;
        padding: 4px;
    }
    .quiz-report-kicker {
        font-size: 0.72rem;
        opacity: 0.9;
        letter-spacing: 0.02em;
    }
    .quiz-report-banner h2 {
        font-size: 1.2rem;
        margin: 0.1rem 0;
        font-weight: 700;
    }
    .quiz-report-banner p {
        margin: 0;
        font-size: 0.78rem;
        opacity: 0.9;
        max-width: 520px;
    }
    .quiz-report-meta {
        text-align: right;
        font-size: 0.82rem;
        white-space: nowrap;
    }
    .quiz-report-meta strong {
        display: block;
        font-size: 0.95rem;
        margin-bottom: 0.2rem;
    }
    .quiz-report-info {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        border-bottom: 1px solid #e2e8f0;
    }
    .quiz-report-info > div {
        padding: 0.85rem 1.1rem;
        border-right: 1px solid #e2e8f0;
    }
    .quiz-report-info > div:last-child { border-right: 0; }
    .quiz-report-info span {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 0.2rem;
    }
    .quiz-report-info strong {
        font-size: 0.92rem;
        color: #0f172a;
    }
    .quiz-report-stats {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .quiz-report-stats > div {
        padding: 0.85rem 0.75rem;
        text-align: center;
        border-right: 1px solid #e2e8f0;
    }
    .quiz-report-stats > div:last-child { border-right: 0; }
    .quiz-report-stats b {
        display: block;
        font-size: 1.2rem;
        line-height: 1.2;
        color: #0f172a;
    }
    .quiz-report-stats small {
        color: #64748b;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .quiz-report-stats .is-pass b { color: #047857; }
    .quiz-report-stats .is-fail b { color: #b91c1c; }
    .quiz-report-note {
        margin: 0;
        padding: 0.65rem 1.1rem;
        font-size: 0.82rem;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }
    .quiz-report-section {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #047857;
        margin: 1.1rem 1.1rem 0.15rem;
        padding-bottom: 0.35rem;
        border-bottom: 1px solid #a7f3d0;
    }
    .quiz-report-section-sub {
        margin: 0 1.1rem 0.65rem;
        font-size: 0.82rem;
        color: #64748b;
    }
    .quiz-report-table {
        margin: 0 0 0.5rem;
    }
    .quiz-report-table th {
        background: #f1f5f9;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #475569;
        white-space: nowrap;
    }
    .quiz-report-table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
    .quiz-report-footer {
        margin-top: 1rem;
        padding: 0.75rem 1.1rem;
        border-top: 1px solid #e2e8f0;
        font-size: 0.78rem;
        color: #64748b;
    }
    .print-only { display: none; }

    @media (max-width: 992px) {
        .quiz-report-info,
        .quiz-report-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        .quiz-report-info > div,
        .quiz-report-stats > div {
            border-bottom: 1px solid #e2e8f0;
        }
    }

    @media print {
        .no-print,
        .app-sidebar,
        .top-navbar,
        .app-footer,
        #sidebar-overlay,
        .alert {
            display: none !important;
        }
        .app-wrapper,
        .app-main,
        .app-main-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        body { background: #fff !important; color: #1f2937; font-size: 11px; }
        .print-only { display: block !important; }

        .quiz-print-document .banner {
            background: #047857;
            color: #fff;
            padding: 12px 14px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .quiz-print-document .banner table { width: 100%; border-collapse: collapse; }
        .quiz-print-document .logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            background: #fff;
            border-radius: 50%;
            padding: 3px;
        }
        .quiz-print-document .brand-kicker { font-size: 8px; margin: 0 0 2px; }
        .quiz-print-document .brand-title { font-size: 15px; font-weight: 700; margin: 0; }
        .quiz-print-document .brand-sub { font-size: 9px; margin: 2px 0 0; }
        .quiz-print-document .meta { text-align: right; font-size: 10px; white-space: nowrap; }
        .quiz-print-document .info { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .quiz-print-document .info td { width: 25%; vertical-align: top; padding: 6px 8px 8px 0; }
        .quiz-print-document .label {
            display: block;
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .quiz-print-document .value {
            display: block;
            font-size: 11px;
            font-weight: 700;
            margin-top: 2px;
        }
        .quiz-print-document .stats { width: 100%; border-collapse: collapse; margin: 4px 0 10px; }
        .quiz-print-document .stats td {
            width: 16.66%;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            text-align: center;
            padding: 7px 4px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .quiz-print-document .stats b { display: block; font-size: 13px; }
        .quiz-print-document .stats span {
            display: block;
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .quiz-print-document .pass { color: #047857; }
        .quiz-print-document .fail { color: #b91c1c; }
        .quiz-print-document h3 {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #047857;
            border-bottom: 1px solid #a7f3d0;
            padding-bottom: 4px;
            margin: 14px 0 4px;
        }
        .quiz-print-document .note,
        .quiz-print-document .sub { font-size: 9px; color: #6b7280; margin: 0 0 8px; }
        .quiz-print-document table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .quiz-print-document table.data th,
        .quiz-print-document table.data td {
            border: 1px solid #d1d5db;
            padding: 5px 6px;
            text-align: left;
            font-size: 10px;
            vertical-align: top;
        }
        .quiz-print-document table.data th {
            background: #f3f4f6;
            font-size: 9px;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .quiz-print-document table.data thead { display: table-header-group; }
        .quiz-print-document .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: 700;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .quiz-print-document .badge-success { background: #d1fae5; color: #065f46; }
        .quiz-print-document .badge-danger { background: #fee2e2; color: #991b1b; }
        .quiz-print-document .badge-warning { background: #fef3c7; color: #92400e; }
        .quiz-print-document .badge-secondary { background: #f3f4f6; color: #374151; }
        .quiz-print-document .empty { text-align: center; color: #6b7280; padding: 10px; }
        .quiz-print-document .footer {
            margin-top: 14px;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
        tr { break-inside: avoid; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/results.blade.php ENDPATH**/ ?>