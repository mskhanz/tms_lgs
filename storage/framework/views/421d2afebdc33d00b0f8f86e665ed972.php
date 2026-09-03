<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app.name')); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --primary-soft: #ecfdf5;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            color: var(--text);
            background: #fff;
            margin: 0;
            overflow-x: hidden;
        }

        .home-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
        }

        .home-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .home-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .home-brand img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            background: #fff;
            border-radius: 50%;
            padding: 4px;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.15);
        }

        .home-brand-text strong {
            display: block;
            font-size: 0.95rem;
            color: var(--primary-dark);
            line-height: 1.2;
        }

        .home-brand-text small {
            font-size: 0.62rem;
            color: var(--muted);
            line-height: 1.35;
            display: block;
            max-width: 240px;
        }

        .home-nav-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-home-primary {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-home-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
        }

        .btn-home-outline {
            border: 1.5px solid var(--primary);
            color: var(--primary-dark);
            background: #fff;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.48rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .btn-home-outline:hover {
            background: var(--primary-soft);
            color: var(--primary-dark);
        }

        .home-hero {
            background: linear-gradient(160deg, #f0fdf4 0%, #ffffff 45%, #ecfdf5 100%);
            padding: 3rem 0 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .home-hero::after {
            content: '';
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.08);
            top: -80px;
            right: -80px;
        }

        .home-hero-content {
            position: relative;
            z-index: 1;
        }

        .home-kicker {
            display: inline-block;
            background: var(--primary-soft);
            color: var(--primary-dark);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            border: 1px solid #bbf7d0;
            margin-bottom: 1rem;
        }

        .home-hero h1 {
            font-size: clamp(1.75rem, 4vw, 2.75rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 0.75rem;
            color: var(--text);
        }

        .home-hero h1 span {
            display: block;
            margin-top: 0.35rem;
            font-size: clamp(0.95rem, 2.2vw, 1.3rem);
            font-weight: 600;
            line-height: 1.35;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .home-hero-lead {
            font-size: 1.05rem;
            color: var(--muted);
            max-width: 640px;
            line-height: 1.65;
            margin-bottom: 1.5rem;
        }

        .home-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .home-hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            max-width: 520px;
        }

        .home-stat-pill {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.75rem 0.85rem;
            text-align: center;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }

        .home-stat-pill i {
            color: var(--primary);
            font-size: 1.1rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .home-stat-pill span {
            font-size: 0.72rem;
            color: var(--muted);
            font-weight: 600;
        }

        .home-section {
            padding: 3rem 0;
        }

        .home-section.alt {
            background: #f8fafc;
        }

        .section-head {
            text-align: center;
            max-width: 640px;
            margin: 0 auto 2rem;
        }

        .section-head h2 {
            font-size: clamp(1.35rem, 3vw, 1.85rem);
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .section-head p {
            color: var(--muted);
            margin: 0;
            font-size: 0.95rem;
        }

        .portal-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        }

        .portal-card:hover {
            transform: translateY(-4px);
            border-color: #a7f3d0;
            box-shadow: 0 10px 28px rgba(5, 150, 105, 0.12);
        }

        .portal-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 1rem;
        }

        .portal-card h3 {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .portal-card p {
            color: var(--muted);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
            line-height: 1.55;
        }

        .portal-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .portal-card ul li {
            font-size: 0.82rem;
            color: #475569;
            padding: 0.25rem 0;
            display: flex;
            align-items: flex-start;
            gap: 0.4rem;
        }

        .portal-card ul li i {
            color: var(--primary);
            margin-top: 0.15rem;
        }

        .feature-grid-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            height: 100%;
            display: flex;
            gap: 0.85rem;
            align-items: flex-start;
        }

        .feature-grid-card .icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--primary-soft);
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .feature-grid-card h4 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .feature-grid-card p {
            font-size: 0.82rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.5;
        }

        .steps-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .step-card {
            text-align: center;
            padding: 1.25rem 1rem;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .step-number {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: #fff;
            font-weight: 800;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .step-card h4 {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .step-card p {
            font-size: 0.8rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.45;
        }

        .home-cta {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
            color: #fff;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            margin: 0 0 3rem;
        }

        .home-cta h2 {
            font-size: clamp(1.35rem, 3vw, 1.85rem);
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .home-cta p {
            opacity: 0.9;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
        }

        .home-cta .btn-home-primary {
            background: #fff;
            color: var(--primary-dark);
        }

        .home-cta .btn-home-primary:hover {
            color: var(--primary-dark);
        }

        .home-cta .btn-home-outline {
            border-color: rgba(255, 255, 255, 0.7);
            color: #fff;
            background: transparent;
        }

        .home-cta .btn-home-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .home-footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 2rem 0;
            font-size: 0.82rem;
        }

        .home-footer strong {
            color: #e2e8f0;
            display: block;
            margin-bottom: 0.35rem;
        }

        .about-highlight {
            background: #fff;
            border: 1px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: 14px;
            padding: 1.25rem 1.35rem;
            height: 100%;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }

        .about-highlight h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.65rem;
            color: var(--primary-dark);
        }

        .about-highlight p {
            font-size: 0.875rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.6;
        }

        .impact-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .impact-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            text-align: center;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        }

        .impact-card strong {
            display: block;
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--primary-dark);
            line-height: 1.1;
        }

        .impact-card span {
            display: block;
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 0.35rem;
            line-height: 1.4;
        }

        .module-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem 1rem;
        }

        .module-list li {
            font-size: 0.82rem;
            color: #475569;
            display: flex;
            align-items: flex-start;
            gap: 0.4rem;
        }

        .module-list li i {
            color: var(--primary);
            margin-top: 0.15rem;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
        }

        .contact-item i {
            color: var(--primary-light);
            font-size: 1.1rem;
            margin-top: 0.15rem;
        }

        .contact-item strong {
            display: block;
            color: #e2e8f0;
            font-size: 0.82rem;
            margin-bottom: 0.2rem;
        }

        .contact-item span,
        .contact-item a {
            color: #94a3b8;
            font-size: 0.82rem;
            text-decoration: none;
        }

        .contact-item a:hover {
            color: var(--primary-light);
        }

        @media (max-width: 991.98px) {
            .steps-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .impact-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .module-list {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .home-nav-inner {
                flex-wrap: wrap;
            }

            .home-nav-actions {
                width: 100%;
                justify-content: stretch;
            }

            .home-nav-actions .btn-home-primary,
            .home-nav-actions .btn-home-outline {
                flex: 1;
                justify-content: center;
            }

            .home-hero {
                padding: 2rem 0 1.5rem;
            }

            .home-hero-stats {
                grid-template-columns: 1fr;
            }

            .steps-row,
            .impact-grid,
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .home-section {
                padding: 2rem 0;
            }

            .home-cta {
                padding: 1.75rem 1.25rem;
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>
    <nav class="home-nav">
        <div class="container home-nav-inner">
            <a href="<?php echo e(route('home')); ?>" class="home-brand">
                <img src="<?php echo e(asset('images/kp-logo.png')); ?>" alt="KP Logo">
                <div class="home-brand-text">
                    <strong><?php echo e(config('app.name')); ?></strong>
                    <small><?php echo e(config('app.tagline')); ?></small>
                </div>
            </a>
            <div class="home-nav-actions">
                <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-home-primary">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-home-outline border-0">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
                <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn-home-outline">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
                <a href="<?php echo e(route('register')); ?>" class="btn-home-primary">
                    <i class="bi bi-person-plus"></i> Register
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section class="home-hero">
        <div class="container home-hero-content">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="home-kicker">Local Government Department · Government of Khyber Pakhtunkhwa</span>
                    <h1><?php echo e(config('app.name')); ?> <span><?php echo e(config('app.tagline')); ?></span></h1>
                    <p class="home-hero-lead">
                        Established in January 2008 and renamed as Local Governance School in 2012, LGS is the dedicated training institute of LCB, LGE &amp; RDD.
                    </p>

                    <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <div class="home-hero-actions">
                        <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="btn-home-primary btn-lg">
                            <i class="bi bi-speedometer2"></i> Go to Dashboard
                        </a>
                        <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn-home-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="btn-home-outline btn-lg">
                            <i class="bi bi-person-plus"></i> Register as Trainee
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="home-hero-stats">
                        <div class="home-stat-pill">
                            <i class="bi bi-people"></i>
                            <span>16,552+ officer trainings</span>
                        </div>
                        <div class="home-stat-pill">
                            <i class="bi bi-bank"></i>
                            <span>10,000+ elected reps trained</span>
                        </div>
                        <div class="home-stat-pill">
                            <i class="bi bi-mortarboard"></i>
                            <span>Pre-Service &amp; Pre-Promotion</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="portal-card">
                        <div class="portal-icon mx-auto"><i class="bi bi-mortarboard"></i></div>
                        <h3 class="text-center">Our mandate</h3>
                        <p class="text-center">Under Section 110 of the KP Local Government Act 2013, members of Local Councils and LG functionaries must attend capacity-building trainings.</p>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> Pre-Service &amp; Pre-Promotion trainings</li>
                            <li><i class="bi bi-check-circle-fill"></i> Trainings for elected representatives</li>
                            <li><i class="bi bi-check-circle-fill"></i> Workshops, TOT &amp; orientation courses</li>
                            <li><i class="bi bi-check-circle-fill"></i> Digital LMS &amp; online training support</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section alt">
        <div class="container">
            <div class="section-head">
                <h2>About Local Governance School</h2>
                <p>KP's dedicated institute for human resource development in local governance and municipal service delivery.</p>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="about-highlight">
                        <h3><i class="bi bi-calendar-event me-2"></i>Established 2008</h3>
                        <p>Initially established as the Local Government Training Institute in January 2008 and later renamed as Local Governance School in 2012 to broaden its scope of work.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-highlight">
                        <h3><i class="bi bi-shield-check me-2"></i>Legal mandate</h3>
                        <p>Section 110 of the KP Local Government Act 2013 makes it mandatory for Local Council members and LG functionaries to attend training courses to enhance their capacity.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-highlight">
                        <h3><i class="bi bi-building me-2"></i>Institutional role</h3>
                        <p>LGS, being the only training institute of LCB, LGE &amp; RDD, ensures compliance and conducts Pre-Service, Pre-Promotion, and elected representative trainings across the province.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-6">
                    <div class="section-head text-start mx-0 mb-3">
                        <h2>Our vision</h2>
                    </div>
                    <div class="about-highlight">
                        <p>Local Governance School is envisaged to become a dedicated institute of Human Resources Development in the arena of Local Governance — for efficient management, effective delivery of basic social and municipal services, and leading towards Good Governance in Khyber Pakhtunkhwa.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-head text-start mx-0 mb-3">
                        <h2>Our objectives</h2>
                    </div>
                    <ul class="module-list">
                        <li><i class="bi bi-check2-circle"></i> Improve professional excellence through trainings and refresher courses</li>
                        <li><i class="bi bi-check2-circle"></i> Equip LG officers and officials with latest administrative and management skills</li>
                        <li><i class="bi bi-check2-circle"></i> Build capacity of LG elected representatives as per the LG Act</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section alt">
        <div class="container">
            <div class="section-head">
                <h2>Training impact (2008–2026)</h2>
                <p>A snapshot of capacity-building delivered by LGS across Khyber Pakhtunkhwa.</p>
            </div>
            <div class="impact-grid mb-4">
                <div class="impact-card">
                    <strong>313</strong>
                    <span>Pre-Service trainees (08 batches)</span>
                </div>
                <div class="impact-card">
                    <strong>300</strong>
                    <span>Pre-Promotion trainees (09 batches)</span>
                </div>
                <div class="impact-card">
                    <strong>3,120</strong>
                    <span>Workshops &amp; short courses participants</span>
                </div>
                <div class="impact-card">
                    <strong>10,000+</strong>
                    <span>Elected representatives trained (approx.)</span>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="portal-card">
                        <div class="portal-icon"><i class="bi bi-journal-bookmark"></i></div>
                        <h3>Training modules</h3>
                        <p>Structured curricula for officers, functionaries, and elected representatives.</p>
                        <ul class="module-list mt-2">
                            <li><i class="bi bi-dot"></i> Planning &amp; development for Tehsil / VC-NC members</li>
                            <li><i class="bi bi-dot"></i> LGAA-19 for elected representatives</li>
                            <li><i class="bi bi-dot"></i> Pre-Service &amp; Pre-Promotion modules</li>
                            <li><i class="bi bi-dot"></i> Gender mainstreaming &amp; municipal services</li>
                            <li><i class="bi bi-dot"></i> Devolved departments training</li>
                            <li><i class="bi bi-dot"></i> COVID-19 awareness module</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="portal-card">
                        <div class="portal-icon"><i class="bi bi-laptop"></i></div>
                        <h3>Digital learning initiatives</h3>
                        <p>Technology-enabled training beyond the classroom.</p>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>Learning Management System (LMS)</strong> — online training for 40,000+ elected representatives and LG functionaries</li>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>Audio-Pedia</strong> — LG system awareness in English, Urdu &amp; Pashto (<a href="http://awareness.lgkp.gov.pk/" target="_blank" rel="noopener">awareness.lgkp.gov.pk</a>)</li>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>IT Lab &amp; Virtual Classroom</strong> — live trainings and ICT-enabled delivery</li>
                            <li><i class="bi bi-check-circle-fill"></i> <strong><?php echo e(config('app.name')); ?></strong> — enrollments, attendance, quizzes &amp; certification tracking</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section alt">
        <div class="container">
            <div class="section-head">
                <h2>Three portals, one platform</h2>
                <p>Each user role gets the tools they need — with a consistent, easy-to-use experience.</p>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="portal-card">
                        <div class="portal-icon"><i class="bi bi-person-badge"></i></div>
                        <h3>Trainee Portal</h3>
                        <p>Complete your profile, view enrollments, track attendance, take quizzes, and download certificates.</p>
                        <ul>
                            <li><i class="bi bi-dot"></i> Profile &amp; qualifications</li>
                            <li><i class="bi bi-dot"></i> My attendance &amp; enrollments</li>
                            <li><i class="bi bi-dot"></i> Quizzes &amp; notifications</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="portal-card">
                        <div class="portal-icon"><i class="bi bi-person-video3"></i></div>
                        <h3>Trainer Portal</h3>
                        <p>Support delivery teams with access to assigned batches, sessions, and trainee progress.</p>
                        <ul>
                            <li><i class="bi bi-dot"></i> Batch &amp; session overview</li>
                            <li><i class="bi bi-dot"></i> Trainee assessments</li>
                            <li><i class="bi bi-dot"></i> Training coordination</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="portal-card">
                        <div class="portal-icon"><i class="bi bi-grid-1x2"></i></div>
                        <h3>Admin Portal</h3>
                        <p>Manage programs, batches, enrollments, attendance, users, roles, and system-wide reporting.</p>
                        <ul>
                            <li><i class="bi bi-dot"></i> Program &amp; batch management</li>
                            <li><i class="bi bi-dot"></i> Bulk enrollment &amp; attendance</li>
                            <li><i class="bi bi-dot"></i> Activity logs &amp; online users</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="container">
            <div class="section-head">
                <h2>Key capabilities</h2>
                <p>Everything you need to run training operations efficiently and transparently.</p>
            </div>
            <div class="row g-3">
                <?php $__currentLoopData = [
                    ['icon' => 'journal-text', 'title' => 'Training Programs', 'desc' => 'Create and manage programs with categories, duration, and batch scheduling.'],
                    ['icon' => 'people', 'title' => 'Enrollment Management', 'desc' => 'Enroll trainees individually or in bulk with seat capacity checks.'],
                    ['icon' => 'calendar-check', 'title' => 'Attendance System', 'desc' => 'Mark session-wise attendance with date rules and change audit trail.'],
                    ['icon' => 'clipboard-check', 'title' => 'Quizzes & Assessments', 'desc' => 'Assign quizzes to batches or programs with attempt limits and scoring.'],
                    ['icon' => 'file-earmark-pdf', 'title' => 'Profile & Reports', 'desc' => 'Download trainee dossiers, attendance reports, and printable summaries.'],
                    ['icon' => 'bell', 'title' => 'Notifications', 'desc' => 'Stay informed with in-app alerts for enrollments and system updates.'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-grid-card">
                        <div class="icon"><i class="bi bi-<?php echo e($feature['icon']); ?>"></i></div>
                        <div>
                            <h4><?php echo e($feature['title']); ?></h4>
                            <p><?php echo e($feature['desc']); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <section class="home-section alt">
        <div class="container">
            <div class="section-head">
                <h2>How to get started</h2>
                <p>New trainees can register and begin their training journey in a few simple steps.</p>
            </div>
            <div class="steps-row">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h4>Register</h4>
                    <p>Create your trainee account with basic details.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h4>Complete Profile</h4>
                    <p>Fill in personal, posting, and qualification information.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h4>Get Enrolled</h4>
                    <p>Admin enrolls you in a training batch or program.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h4>Train &amp; Track</h4>
                    <p>Attend sessions, take quizzes, and earn certificates.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="home-cta">
            <h2>Ready to get started?</h2>
            <p>Sign in to your account or register as a new trainee to access <?php echo e(config('app.name')); ?>.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-home-primary btn-lg">
                    <i class="bi bi-speedometer2"></i> Open Dashboard
                </a>
                <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn-home-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </a>
                <a href="<?php echo e(route('register')); ?>" class="btn-home-outline btn-lg">
                    <i class="bi bi-person-plus"></i> Register Now
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="home-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <strong><?php echo e(config('app.name')); ?></strong>
                    <span class="d-block text-muted small mt-1"><?php echo e(config('app.tagline')); ?></span>
                    Local Government Department<br>
                    Government of Khyber Pakhtunkhwa
                    <p class="mt-3 mb-0">Building-33, Street-13, Sector-E-8, Phase-VII, Hayatabad, Peshawar, KP</p>
                </div>
                <div class="col-lg-7">
                    <div class="contact-grid">
                        <div class="contact-item">
                            <i class="bi bi-globe"></i>
                            <div>
                                <strong>Website</strong>
                                <a href="https://lgs.gkp.pk" target="_blank" rel="noopener">lgs.gkp.pk</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <strong>Email</strong>
                                <a href="mailto:Lgs.lcb.kp@gmail.com">Lgs.lcb.kp@gmail.com</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-telephone"></i>
                            <div>
                                <strong>Phone</strong>
                                <span>091-9219024</span>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-mortarboard"></i>
                            <div>
                                <strong>LGS LMS</strong>
                                <a href="https://lgs.gkp.pk/lms" target="_blank" rel="noopener">lgs.gkp.pk/lms</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4 opacity-25">
            <div class="text-center">
                &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>, Local Governance School, Government of Khyber Pakhtunkhwa. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/welcome.blade.php ENDPATH**/ ?>