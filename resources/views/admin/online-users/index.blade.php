@extends('layouts.admin')

@section('title', 'Online Users')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-broadcast me-2"></i>Online Users</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Online Users</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.login-history.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-clock-history me-2"></i>Login history
            </a>
            <a href="{{ route('admin.online-users.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Currently online</div>
                    <div class="fs-3 fw-semibold">{{ $onlineCount }}</div>
                </div>
                <span class="online-dot"></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Open sessions</div>
                <div class="fs-3 fw-semibold">{{ $openCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Logins today</div>
                <div class="fs-3 fw-semibold">{{ $loginsToday }}</div>
            </div>
        </div>
    </div>
</div>

<p class="text-muted small mb-3">
    A user is shown as online if they were active in the last {{ (int) config('activity.online_minutes', 5) }} minutes.
    This page refreshes every 30 seconds.
</p>

<div class="card mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong>Online now</strong>
        <span class="badge bg-success">{{ $onlineCount }}</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Logged in</th>
                        <th>Duration</th>
                        <th>Last activity</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($session->user && $session->user->photoUrl())
                                    <img src="{{ $session->user->photoUrl() }}" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                                @else
                                    <div style="width: 40px; height: 40px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; margin-right: 12px;">
                                        {{ strtoupper(substr($session->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">
                                        <span class="online-dot me-1"></span>
                                        {{ $session->user->name ?? 'Unknown user' }}
                                    </div>
                                    <small class="text-muted">{{ $session->user->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($session->user->user_type ?? '—') }}</span>
                        </td>
                        <td>{{ $session->logged_in_at?->format('d M Y, h:i A') }}</td>
                        <td class="fw-semibold">{{ $session->durationLabel() }}</td>
                        <td>{{ $session->last_activity_at?->diffForHumans() }}</td>
                        <td><code>{{ $session->ip_address ?? '—' }}</code></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-wifi-off" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">No users are online right now</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($idleSessions->isNotEmpty())
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong>Idle sessions</strong>
        <span class="badge bg-warning text-dark">{{ $idleSessions->count() }}</span>
    </div>
    <div class="card-body">
        <p class="text-muted small">These users still have an open session but have been inactive for more than {{ (int) config('activity.online_minutes', 5) }} minutes.</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Logged in</th>
                        <th>Duration</th>
                        <th>Last activity</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($idleSessions as $session)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $session->user->name ?? 'Unknown user' }}</div>
                            <small class="text-muted">{{ $session->user->email ?? '' }}</small>
                        </td>
                        <td>{{ $session->logged_in_at?->format('d M Y, h:i A') }}</td>
                        <td>{{ $session->durationLabel() }}</td>
                        <td>{{ $session->last_activity_at?->diffForHumans() }}</td>
                        <td><code>{{ $session->ip_address ?? '—' }}</code></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
setTimeout(function () {
    window.location.reload();
}, 30000);
</script>
@endpush
