<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Login'); ?> — <?php echo e(config('app.name')); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --primary-muted: #065f46;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100dvh;
            margin: 0;
            display: flex;
            overflow-x: hidden;
        }

        .auth-shell {
            display: flex;
            width: 100%;
            min-height: 100dvh;
        }

        .auth-left {
            width: 58%;
            min-height: 100dvh;
            background: linear-gradient(150deg, var(--primary-dark) 0%, var(--primary) 40%, var(--primary-light) 70%, var(--primary-muted) 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            border: 80px solid rgba(255, 255, 255, 0.04);
            top: -150px;
            left: -150px;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            border: 60px solid rgba(255, 255, 255, 0.05);
            bottom: -120px;
            right: -100px;
        }

        .left-circle-mid {
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            border: 40px solid rgba(255, 255, 255, 0.04);
            bottom: 80px;
            left: 40px;
        }

        .left-inner {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 420px;
        }

        .left-inner .brand-logo {
            margin-bottom: 1.8rem;
            background: #ffffff;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.2);
            padding: 14px 22px;
        }

        .left-inner .brand-logo img {
            max-height: 110px;
            max-width: 200px;
            object-fit: contain;
            display: block;
        }

        .left-inner h1 {
            color: #ffffff;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
        }

        .left-inner p.subtitle {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 2.5rem;
        }

        .left-divider {
            width: 60px;
            height: 3px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 2px;
            margin: 0 auto 2rem;
        }

        .left-features {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            text-align: left;
        }

        .left-feature {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.875rem;
        }

        .left-feature .feat-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .auth-main {
            width: 42%;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            min-height: 100dvh;
        }

        .auth-main-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem 1.5rem;
            width: 100%;
        }

        .auth-right-inner {
            width: 100%;
            max-width: 360px;
        }

        .auth-right-inner .welcome-text {
            margin-bottom: 2rem;
        }

        .auth-right-inner .welcome-text h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.3rem;
        }

        .auth-right-inner .welcome-text p {
            color: #64748b;
            font-size: 0.875rem;
        }

        .form-floating-custom {
            position: relative;
            margin-bottom: 1.1rem;
        }

        .form-floating-custom label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
            display: block;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            z-index: 2;
        }

        .input-wrap input {
            width: 100%;
            height: 48px;
            padding: 0 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            background: #ffffff;
            color: #1e293b;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .input-wrap input.is-invalid {
            border-color: #ef4444;
        }

        .input-wrap .toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            font-size: 1rem;
            line-height: 1;
        }

        .invalid-feedback {
            font-size: 0.78rem;
            color: #ef4444;
            margin-top: 0.3rem;
            display: block;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .remember-row .remember-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-row input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-row label {
            font-size: 0.83rem;
            color: #64748b;
            cursor: pointer;
            margin: 0;
        }

        .forgot-link {
            font-size: 0.83rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
        }

        .btn-signin {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.03em;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-signin:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .auth-footer-right {
            flex-shrink: 0;
            width: 100%;
            padding: 0.85rem 1.5rem calc(0.85rem + env(safe-area-inset-bottom, 0px));
            text-align: center;
            color: #94a3b8;
            font-size: 0.72rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            line-height: 1.5;
        }

        .auth-register-link {
            margin-top: 1.25rem;
            text-align: center;
            font-size: 0.875rem;
            color: #64748b;
        }

        .auth-register-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-register-link a:hover {
            color: var(--primary-dark);
        }

        .auth-home-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.75rem;
            font-size: 0.82rem;
            color: #94a3b8;
            text-decoration: none;
        }

        .auth-home-link:hover {
            color: var(--primary);
        }

        .alert-auth {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.83rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .alert-auth.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-auth.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }

        .alert-auth.warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #b45309;
        }

        @media (max-width: 768px) {
            body {
                min-height: 100dvh;
                height: auto;
            }

            .auth-shell {
                flex-direction: column;
                min-height: 100dvh;
            }

            .auth-left {
                width: 100%;
                padding: 2rem 1.25rem 1.75rem;
                min-height: 260px;
                flex-shrink: 0;
                justify-content: center;
            }

            .auth-left .left-circle-mid,
            .auth-left .left-features,
            .left-divider {
                display: none;
            }

            .left-inner .brand-logo {
                margin-bottom: 1.1rem;
                padding: 14px 20px;
                border-radius: 14px;
            }

            .left-inner .brand-logo img {
                max-height: 72px;
            }

            .left-inner h1 {
                font-size: 1.12rem;
                line-height: 1.4;
                margin-bottom: 0.4rem;
            }

            .left-inner p.subtitle {
                font-size: 0.85rem;
                margin-bottom: 0;
            }

            .auth-left::before,
            .auth-left::after {
                display: none;
            }

            .auth-main {
                width: 100%;
                flex: 1;
                min-height: 0;
            }

            .auth-main-body {
                flex: 1;
                justify-content: flex-start;
                padding: 1.15rem 1.25rem 0.75rem;
            }

            .auth-right-inner .welcome-text {
                margin-bottom: 1.15rem;
            }

            .auth-right-inner .welcome-text h2 {
                font-size: 1.35rem;
            }

            .form-floating-custom {
                margin-bottom: 0.9rem;
            }

            .remember-row {
                margin-bottom: 1.1rem;
            }
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="auth-left">
        <div class="left-circle-mid"></div>
        <div class="left-inner">
            <div class="brand-logo">
                <img src="<?php echo e(asset('images/kp-logo.png')); ?>" alt="KP Logo">
            </div>
            <h1><?php echo e(config('app.name')); ?></h1>
            <p class="subtitle"><?php echo e(config('app.tagline')); ?><br>Government of Khyber Pakhtunkhwa</p>
            <div class="left-divider"></div>
            <div class="left-features">
                <div class="left-feature">
                    <div class="feat-icon"><i class="bi bi-journal-bookmark text-white"></i></div>
                    <span>Training programs, batches &amp; enrollments</span>
                </div>
                <div class="left-feature">
                    <div class="feat-icon"><i class="bi bi-calendar-check text-white"></i></div>
                    <span>Session-wise attendance tracking</span>
                </div>
                <div class="left-feature">
                    <div class="feat-icon"><i class="bi bi-clipboard-check text-white"></i></div>
                    <span>Quizzes, assessments &amp; certificates</span>
                </div>
                <div class="left-feature">
                    <div class="feat-icon"><i class="bi bi-shield-check text-white"></i></div>
                    <span>Secure role-based access for trainees &amp; staff</span>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-main-body">
            <div class="auth-right-inner">
                <div class="welcome-text">
                    <h2>Welcome Back</h2>
                    <p>Sign in to your account to continue</p>
                </div>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>

        <div class="auth-footer-right">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>, Local Governance School, Government of Khyber Pakhtunkhwa &mdash; All rights reserved.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/layouts/auth.blade.php ENDPATH**/ ?>