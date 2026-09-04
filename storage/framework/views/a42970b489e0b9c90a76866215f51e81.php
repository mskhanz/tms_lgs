<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - <?php echo e(config('app.name')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-card {
            max-width: 480px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2rem;
            text-align: center;
        }
        .error-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #ecfdf5;
            color: #059669;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon"><i class="bi bi-compass"></i></div>
        <h1 class="h3 mb-2">404 - Page Not Found</h1>
        <p class="text-muted mb-4">The page you are looking for does not exist or may have been moved.</p>
        <div class="d-grid gap-2">
            <a href="<?php echo e(route('home')); ?>" class="btn btn-success">Go to Home</a>
            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline-secondary">Go to Dashboard</a>
            <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-secondary">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/errors/404.blade.php ENDPATH**/ ?>