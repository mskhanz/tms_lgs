<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginSession;
use App\Models\User;
use App\Services\LoginSessionTracker;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
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

    public function index(Request $request, LoginSessionTracker $tracker)
    {
        $tracker->expireStale();

        $query = LoginSession::with('user')->latest('logged_in_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'online') {
                $query->online();
            } elseif ($request->status === 'open') {
                $query->open();
            } elseif ($request->status === 'closed') {
                $query->whereNotNull('logged_out_at');
            }
        }

        if ($request->filled('from')) {
            $query->whereDate('logged_in_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('logged_in_at', '<=', $request->to);
        }

        $sessions = $query->paginate(25)->withQueryString();
        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.login-history.index', compact('sessions', 'users'));
    }
}
