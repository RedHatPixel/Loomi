<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function index(Request $request): Response
    {
        $notifications = DatabaseNotification::where('notifiable_id', $request->user()->id)
            ->where('notifiable_type', $request->user()::class)
            ->latest()
            ->paginate(20);

        $unreadCount = DatabaseNotification::where('notifiable_id', $request->user()->id)
            ->where('notifiable_type', $request->user()::class)
            ->whereNull('read_at')
            ->count();

        return Inertia::render('Customer/Notifications/Index', [
            'all_notifications' => collect($notifications->items())->map(fn ($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'data'       => $n->data,
                'read_at'    => $n->read_at?->toDateTimeString(),
                'created_at' => $n->created_at->toDateTimeString(),
            ]),
            'unread_count' => $unreadCount,
            'total_count'  => $notifications->total(),
            'pagination'   => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'total'        => $notifications->total(),
                'per_page'     => $notifications->perPage(),
            ],
        ]);
    }
}
