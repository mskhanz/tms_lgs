@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-activity me-2"></i>Activity Logs</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Activity Logs</li>
        </ol>
    </nav>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Action, user, or IP" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Module</label>
                <select name="log_name" class="form-select">
                    <option value="">All modules</option>
                    @foreach($logNames as $name)
                        <option value="{{ $name }}" {{ request('log_name') == $name ? 'selected' : '' }}>{{ ucfirst($name) }}</option>
                    @endforeach
                </select>
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
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>IP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $loop->index }}</td>
                        <td>
                            <div>{{ $log->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $log->created_at->format('h:i:s A') }}</small>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="fw-semibold">{{ $log->user->name }}</div>
                                <small class="text-muted">{{ $log->user->email }}</small>
                            @else
                                <span class="text-muted">System / guest</span>
                            @endif
                        </td>
                        <td>{{ $log->description }}</td>
                        <td>
                            @if($log->log_name)
                                <span class="badge bg-light text-dark text-uppercase">{{ $log->log_name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><code>{{ $log->ip_address ?? '—' }}</code></td>
                        <td>
                            <a href="{{ route('admin.activity-logs.show', $log->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">No activity logs found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $logs, 'label' => 'logs'])
    </div>
</div>
@endsection
