<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $this->authorize('update', $notification);

        $notification->markAsRead();

        return redirect()->to($notification->data['link'] ?? route('dashboard'));
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index')
            ->with('success', 'All notifications marked as read');
    }

    public function redirect(DatabaseNotification $notification)
    {
        // $this->authorize('update', $notification);

        $notification->markAsRead();

        return redirect()->to($notification->data['link'] ?? route('dashboard'));
    }
}
