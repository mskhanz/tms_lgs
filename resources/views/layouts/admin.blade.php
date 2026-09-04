<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}?v={{ @filemtime(public_path('css/admin-style.css')) }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    @stack('styles')
</head>
<body>
    <div id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside class="app-sidebar" id="app-sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('images/kp-logo.png') }}" alt="Logo" class="brand-logo-img" onerror="this.style.display='none'">
            <div class="brand-text">
                {{ config('app.name') }}
                <small>{{ config('app.tagline') }}</small>
            </div>
        </a>

        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <!-- Dashboard -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('dashboard') }}" 
                       class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(! auth()->user()->isTrainee())
                <li class="sidebar-menu-item">
                    <a href="{{ route('account.profile') }}"
                       class="sidebar-menu-link {{ request()->routeIs('account.profile', 'account.profile.edit') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->isTrainee())
                <!-- Trainee Menu -->
                <li class="sidebar-menu-header">Trainee</li>
                
                <li class="sidebar-menu-item">
                    <a href="{{ route('trainee.profile.show') }}" 
                       class="sidebar-menu-link {{ request()->routeIs('trainee.profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span>My Profile</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('trainee.dashboard') }}" 
                       class="sidebar-menu-link {{ request()->routeIs('trainee.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>My Enrollments</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('trainee.quizzes.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('trainee.quizzes.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>My Quizzes</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('trainee.assignments.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('trainee.assignments.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>My Assignments</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-award"></i>
                        <span>My Certificates</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('trainee.attendance.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('trainee.attendance.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('notifications.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="bi bi-bell"></i>
                        <span>Notifications</span>
                        @if(($navUnreadCount ?? 0) > 0)
                        <span class="badge bg-danger ms-auto badge-blink">{{ ($navUnreadCount ?? 0) > 9 ? '9+' : $navUnreadCount }}</span>
                        @endif
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasRole(['system_admin', 'director', 'deputy_director', 'training_officer']))
                <!-- Administration -->
                <li class="sidebar-menu-header">Administration</li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid"></i>
                        <span>Admin Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-item has-submenu {{ request()->routeIs('admin.programs.*') || request()->routeIs('admin.batches.*') ? 'open' : '' }}">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-book"></i>
                        <span>Training Programs</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.programs.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.programs.index', 'admin.programs.show', 'admin.programs.edit') ? 'active' : '' }}">
                                <i class="bi bi-list-ul"></i> All Programs
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.programs.create') }}" class="sidebar-menu-link {{ request()->routeIs('admin.programs.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-circle"></i> Create Program
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.batches.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.batches.index', 'admin.batches.show', 'admin.batches.edit') ? 'active' : '' }}">
                                <i class="bi bi-collection"></i> Training Batches
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.batches.create') }}" class="sidebar-menu-link {{ request()->routeIs('admin.batches.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-square"></i> Create Batch
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item has-submenu {{ request()->routeIs('admin.enrollments.*') ? 'open' : '' }}">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-person-check"></i>
                        <span>Enrollments</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.enrollments.index') }}" class="sidebar-menu-link">
                                <i class="bi bi-list-check"></i> All Enrollments
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.enrollments.create') }}" class="sidebar-menu-link">
                                <i class="bi bi-plus-circle"></i> New Enrollment
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.trainees.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-people"></i>
                        <span>Trainees</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-person-video3"></i>
                        <span>Trainers</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.attendance.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('admin.attendance.*', 'admin.batches.attendance.*', 'admin.programs.attendance.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar2-week"></i>
                        <span>Attendance</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.registration-trainings.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-mortarboard"></i>
                        <span>Registration Trainings</span>
                    </a>
                </li>

                <li class="sidebar-menu-item has-submenu {{ request()->routeIs('admin.quizzes.*') ? 'open' : '' }}">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Quizzes</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.quizzes.index') }}" class="sidebar-menu-link">
                                <i class="bi bi-list-task"></i> All Quizzes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.quizzes.create') }}" class="sidebar-menu-link">
                                <i class="bi bi-plus-circle"></i> Create Quiz
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item has-submenu {{ request()->routeIs('admin.assignments.*') ? 'open' : '' }}">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Assignments</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.assignments.index') }}" class="sidebar-menu-link">
                                <i class="bi bi-list-task"></i> All Assignments
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.assignments.create') }}" class="sidebar-menu-link">
                                <i class="bi bi-plus-circle"></i> Create Assignment
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-award"></i>
                        <span>Certificates</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-bar-chart"></i>
                        <span>Reports</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasRole(['system_admin', 'director']))
                <!-- System Settings -->
                <li class="sidebar-menu-header">System</li>

                <li class="sidebar-menu-item has-submenu">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-people-fill"></i>
                        <span>User Management</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="sidebar-menu-link">
                                <i class="bi bi-person-lines-fill"></i> Users
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.roles.index') }}" class="sidebar-menu-link">
                                <i class="bi bi-shield-check"></i> Roles & Permissions
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item has-submenu {{ request()->routeIs('admin.activity-logs.*', 'admin.online-users.*', 'admin.login-history.*') ? 'open' : '' }}">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-clipboard-data"></i>
                        <span>Logs & Audit</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                                <i class="bi bi-activity"></i> Activity Logs
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.online-users.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.online-users.*') ? 'active' : '' }}">
                                <i class="bi bi-broadcast"></i> Online Users
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.login-history.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.login-history.*') ? 'active' : '' }}">
                                <i class="bi bi-clock-history"></i> Login History
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-gear"></i>
                        <span>System Settings</span>
                    </a>
                </li>
                @endif

                <!-- Help & Support -->
                <li class="sidebar-menu-header">Help</li>
                @if(! auth()->user()->isTrainee())
                <li class="sidebar-menu-item">
                    <a href="{{ route('notifications.index') }}"
                       class="sidebar-menu-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="bi bi-bell"></i>
                        <span>Notifications</span>
                        @if(($navUnreadCount ?? 0) > 0)
                        <span class="badge bg-danger ms-auto badge-blink">{{ ($navUnreadCount ?? 0) > 9 ? '9+' : $navUnreadCount }}</span>
                        @endif
                    </a>
                </li>
                @endif
                <li class="sidebar-menu-item">
                    <a href="#" class="sidebar-menu-link">
                        <i class="bi bi-question-circle"></i>
                        <span>Help & Support</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="app-main-wrapper" id="app-main-wrapper">
        <header class="top-navbar">
            <button class="btn-toggle-sidebar" id="sidebarToggle" type="button" title="Toggle Sidebar" aria-label="Toggle Sidebar">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div class="top-navbar-title d-sm-none">@yield('title', 'Dashboard')</div>

            <div class="top-navbar-actions ms-auto">
                <span class="date-pill d-none d-md-inline-flex align-items-center gap-1">
                    <i class="bi bi-calendar3"></i>
                    {{ now()->format('l, d M Y') }}
                </span>

                <div class="dropdown top-navbar-notifications">
                    <button class="btn btn-sm top-navbar-pill notification-bell d-flex align-items-center gap-2 position-relative"
                            type="button" data-bs-toggle="dropdown" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                        @if(($navUnreadCount ?? 0) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger top-navbar-badge badge-blink">
                            {{ $navUnreadCount > 9 ? '9+' : $navUnreadCount }}
                        </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Notifications</span>
                            @if(($navUnreadCount ?? 0) > 0)
                            <span class="badge rounded-pill top-navbar-notif-count badge-blink">{{ $navUnreadCount }}</span>
                            @endif
                        </li>
                        <li><hr class="dropdown-divider my-0"></li>

                        @forelse(($navNotifications ?? collect()) as $notification)
                        <li>
                            <a class="dropdown-item {{ $notification->isUnread() ? 'bg-light' : '' }}"
                               href="{{ route('notifications.read', $notification) }}">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-{{ $notification->icon() }} me-2 mt-1 text-success"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small">{{ $notification->title }}</div>
                                        <div class="small text-muted">{{ Str::limit($notification->message, 80) }}</div>
                                        <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @empty
                        <li class="text-center py-3 text-muted">
                            <i class="bi bi-bell-slash"></i> No notifications
                        </li>
                        @endforelse

                        <li><hr class="dropdown-divider my-0"></li>
                        <li>
                            <a class="dropdown-item text-center small fw-semibold text-success" href="{{ route('notifications.index') }}">
                                View all notifications <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="dropdown top-navbar-user-menu">
                    <button class="btn btn-sm top-navbar-pill d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-label="User menu">
                        <div class="user-avatar user-avatar-sm">
                            @php
                                $navPhoto = auth()->user()->photoUrl();
                            @endphp
                            @if($navPhoto)
                                <img src="{{ $navPhoto }}" alt="{{ auth()->user()->name }}">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <span class="d-none d-md-inline small fw-semibold top-navbar-username">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 18) }}</span>
                        <i class="bi bi-chevron-down small d-none d-md-inline"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="min-width: 220px;">
                        <li class="px-3 py-2">
                            <div class="fw-semibold">{{ auth()->user()->name }}</div>
                            <div class="small text-muted">{{ auth()->user()->email }}</div>
                            <div class="small text-muted">{{ ucfirst(auth()->user()->user_type) }}</div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item small" href="{{ auth()->user()->isTrainee() ? route('trainee.profile.show') : route('account.profile') }}">
                                <i class="bi bi-person-circle me-2"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item small" href="{{ route('account.password.edit') }}">
                                <i class="bi bi-key me-2"></i>Change Password
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item small text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="app-main" id="app-main">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
        </main>

        <footer class="app-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}, Local Governance School, Government of Khyber Pakhtunkhwa. All rights reserved.</p>
        </footer>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const appSidebar = document.getElementById('app-sidebar');
        const appMainWrapper = document.getElementById('app-main-wrapper');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function isMobileSidebar() {
            return window.matchMedia('(max-width: 991.98px)').matches;
        }

        function closeSidebar() {
            appSidebar.classList.remove('active');
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('show');
            }
            document.body.classList.remove('sidebar-open');
        }

        function openSidebar() {
            appSidebar.classList.add('active');
            if (sidebarOverlay) {
                sidebarOverlay.classList.add('show');
            }
            document.body.classList.add('sidebar-open');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function (e) {
                e.preventDefault();
                if (isMobileSidebar()) {
                    if (appSidebar.classList.contains('active')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                } else {
                    appSidebar.classList.toggle('collapsed');
                    if (appMainWrapper) {
                        appMainWrapper.classList.toggle('expanded');
                    }
                }
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        // Sidebar Submenu Toggle
        document.querySelectorAll('.sidebar-menu-item.has-submenu > .sidebar-menu-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;

                document.querySelectorAll('.sidebar-menu-item.has-submenu').forEach(item => {
                    if (item !== parent) {
                        item.classList.remove('open');
                    }
                });

                parent.classList.toggle('open');
            });
        });

        appSidebar.querySelectorAll('.sidebar-menu-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobileSidebar()) {
                    setTimeout(closeSidebar, 120);
                }
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && appSidebar.classList.contains('active')) {
                closeSidebar();
            }
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
