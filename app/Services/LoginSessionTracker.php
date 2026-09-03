<?php

namespace App\Services;

use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Http\Request;
use App\Support\SchemaCache;
use Illuminate\Support\Facades\Cache;

class LoginSessionTracker
{
    public function start(User $user, Request $request, bool $log = true): ?LoginSession
    {
        if (! $this->ready()) {
            return null;
        }

        $this->expireStale();

        $session = LoginSession::create([
            'user_id' => $user->id,
            'session_id' => $this->sessionId($request),
            'ip_address' => $request->ip(),
            'user_agent' => $this->userAgent($request),
            'logged_in_at' => now(),
            'last_activity_at' => now(),
        ]);

        if ($log) {
            activity()
                ->useLog('auth')
                ->causedBy($user)
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'session_id' => $session->session_id,
                ])
                ->log('Logged in');
        }

        return $session;
    }

    public function touch(User $user, Request $request): void
    {
        if (! $this->ready()) {
            return;
        }

        $this->expireStale();

        $sessionId = $this->sessionId($request);
        $session = LoginSession::query()
            ->open()
            ->where('user_id', $user->id)
            ->when($sessionId, fn ($query) => $query->where('session_id', $sessionId))
            ->latest('logged_in_at')
            ->first();

        if (! $session) {
            $this->start($user, $request, false);
            return;
        }

        $interval = max(10, (int) config('activity.touch_interval_seconds', 30));
        if ($session->last_activity_at && $session->last_activity_at->gt(now()->subSeconds($interval))) {
            return;
        }

        $session->update([
            'last_activity_at' => now(),
            'ip_address' => $request->ip() ?: $session->ip_address,
            'user_agent' => $this->userAgent($request) ?: $session->user_agent,
        ]);
    }

    public function end(?User $user, Request $request, string $reason = 'logout'): void
    {
        if (! $user || ! $this->ready()) {
            return;
        }

        $sessionId = $this->sessionId($request);

        $query = LoginSession::query()
            ->open()
            ->where('user_id', $user->id);

        if ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        $updated = $query->update([
            'logged_out_at' => now(),
            'last_activity_at' => now(),
            'logout_reason' => $reason,
        ]);

        if ($updated) {
            activity()
                ->useLog('auth')
                ->causedBy($user)
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'reason' => $reason,
                ])
                ->log('Logged out');
        }
    }

    public function expireStale(): void
    {
        if (! $this->ready()) {
            return;
        }

        if (Cache::get('login_sessions.expired_recently')) {
            return;
        }

        Cache::put('login_sessions.expired_recently', true, now()->addMinutes(5));

        $lifetime = max(1, (int) config('session.lifetime', 120));

        LoginSession::query()
            ->open()
            ->where('last_activity_at', '<', now()->subMinutes($lifetime))
            ->update([
                'logged_out_at' => now(),
                'logout_reason' => 'expired',
            ]);
    }

    public function onlineCount(): int
    {
        if (! $this->ready()) {
            return 0;
        }

        $this->expireStale();

        return LoginSession::query()->online()->count();
    }

    private function ready(): bool
    {
        return SchemaCache::hasTable('login_sessions');
    }

    private function sessionId(Request $request): ?string
    {
        try {
            return $request->hasSession() ? $request->session()->getId() : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function userAgent(Request $request): ?string
    {
        $agent = (string) $request->userAgent();

        return $agent !== '' ? substr($agent, 0, 500) : null;
    }
}
