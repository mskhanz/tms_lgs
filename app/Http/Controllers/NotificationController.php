<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = Auth::user()->notifications()->unread()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function read(Notification $notification)
    {
        abort_unless((int) $notification->user_id === (int) Auth::id(), 403);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return redirect($notification->actionUrl());
    }

    public function markAllRead(Request $request)
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        return redirect()
            ->route('notifications.index')
            ->with('success', 'All notifications marked as read.');
    }
}
