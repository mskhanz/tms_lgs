<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quiz Result Report - <?php echo e($quiz->title); ?></title>
    <style>
        @page { margin: 18px 20px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        .banner { background: #047857; color: #fff; padding: 12px 14px; }
        .banner table { width: 100%; }
        .logo { width: 42px; height: 42px; }
        .brand-kicker { font-size: 8px; margin: 0 0 2px; }
        .brand-title { font-size: 15px; font-weight: bold; margin: 0; }
        .brand-sub { font-size: 9px; margin: 2px 0 0; }
        .meta { text-align: right; font-size: 10px; }
        .info { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info td { width: 25%; vertical-align: top; padding: 6px 8px 8px 0; }
        .label { display: block; font-size: 8px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.4px; }
        .value { display: block; font-size: 11px; font-weight: bold; margin-top: 2px; }
        .stats { width: 100%; border-collapse: collapse; margin: 4px 0 10px; }
        .stats td { width: 16.66%; border: 1px solid #d1d5db; background: #f8fafc; text-align: center; padding: 7px 4px; }
        .stats b { display: block; font-size: 13px; }
        .stats span { display: block; font-size: 8px; color: #6b7280; text-transform: uppercase; margin-top: 2px; }
        .pass { color: #047857; }
        .fail { color: #b91c1c; }
        h3 { font-size: 10px; text-transform: uppercase; letter-spacing: 0.7px; color: #047857; border-bottom: 1px solid #a7f3d0; padding-bottom: 4px; margin: 14px 0 4px; }
        .note, .sub { font-size: 9px; color: #6b7280; margin: 0 0 8px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        table.data th, table.data td { border: 1px solid #d1d5db; padding: 5px 6px; text-align: left; font-size: 10px; vertical-align: top; }
        table.data th { background: #f3f4f6; font-size: 9px; text-transform: uppercase; }
        .badge { display: inline-block; padding: 2px 6px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-secondary { background: #f3f4f6; color: #374151; }
        .empty { text-align: center; color: #6b7280; padding: 10px; }
        .footer { margin-top: 14px; font-size: 9px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 6px; }
    </style>
</head>
<body>
    <?php echo $__env->make('admin.quizzes._results-document', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/quizzes/results-pdf.blade.php ENDPATH**/ ?>