@extends('layouts.admin')

@section('title', 'Login History')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-clock-history me-2"></i>Login History</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Login History</li>
        </ol>
    </nav>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.login-history.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name, email, or IP" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">User</label>
                <select name="user_id" class="form-select">
                    <option value="">All users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open session</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ended</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">To</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <a href="{{ route('admin.login-history.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Logged in</th>
                        <th>Logged out</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr>
                        <td>{{ $sessions->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $session->user->name ?? 'Unknown user' }}</div>
                            <small class="text-muted">{{ $session->user->email ?? '' }}</small>
                        </td>
                        <td>{{ $session->logged_in_at?->format('d M Y, h:i A') }}</td>
                        <td>{{ $session->logged_out_at ? $session->logged_out_at->format('d M Y, h:i A') : '—' }}</td>
                        <td class="fw-semibold">{{ $session->durationLabel() }}</td>
                        <td>
                            <span class="badge bg-{{ $session->statusBadgeClass() }}">{{ $session->statusLabel() }}</span>
                        </td>
                        <td><code>{{ $session->ip_address ?? '—' }}</code></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">No login history found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $sessions, 'label' => 'sessions'])
    </div>
</div>
@endsection
