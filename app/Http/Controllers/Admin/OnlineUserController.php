<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginSession;
use App\Services\LoginSessionTracker;

class OnlineUserController extends Controller
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

    public function index(LoginSessionTracker $tracker)
    {
        $tracker->expireStale();

        $sessions = LoginSession::with('user.roles')
            ->online()
            ->orderByDesc('last_activity_at')
            ->get();

        $idleSessions = LoginSession::with('user.roles')
            ->open()
            ->where('last_activity_at', '<', now()->subMinutes((int) config('activity.online_minutes', 5)))
            ->orderByDesc('last_activity_at')
            ->get();

        $onlineCount = $sessions->count();
        $openCount = $onlineCount + $idleSessions->count();
        $loginsToday = LoginSession::query()->whereDate('logged_in_at', today())->count();

        return view('admin.online-users.index', compact(
            'sessions',
            'idleSessions',
            'onlineCount',
            'openCount',
            'loginsToday'
        ));
    }
}
