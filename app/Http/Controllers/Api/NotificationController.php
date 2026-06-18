<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, DatabaseNotification $notification): RedirectResponse
    {
        abort_if($notification->notifiable_id !== $request->user()->id, 404);

        $notification->markAsRead();

        return back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        DatabaseNotification::where('notifiable_id', $request->user()->id)
            ->where('notifiable_type', $request->user()::class)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back();
    }

}
