<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! $request->user()?->hasRole(['system_admin', 'director'])) {
                abort(403, 'You do not have access to activity monitoring.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->paginate(25)->withQueryString();
        $logNames = ActivityLog::query()
            ->whereNotNull('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');
        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.activity-logs.index', compact('logs', 'logNames', 'users'));
    }

    public function show($id)
    {
        $log = ActivityLog::with(['user', 'subject', 'causer'])->findOrFail($id);

        return view('admin.activity-logs.show', compact('log'));
    }
}
