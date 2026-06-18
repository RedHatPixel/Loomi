<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancelled extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public ?string $storeName = null) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $message = $this->storeName
            ? "Items from {$this->storeName} in order #{$this->order->id} have been cancelled."
            : "Order #{$this->order->id} has been cancelled.";

        $title = $this->storeName ? 'Items cancelled' : 'Order cancelled';

        return [
            'type'    => 'order_cancelled',
            'title'   => $title,
            'message' => $message,
            'link'    => route('orders.show', $this->order->id),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->storeName
            ? "Items from {$this->storeName} in order #{$this->order->id} have been cancelled."
            : "Order #{$this->order->id} has been cancelled.";

        return (new MailMessage)
            ->subject($this->storeName ? 'Items Cancelled' : 'Order Cancelled')
            ->greeting('Order update')
            ->line($message)
            ->action('View Order', route('orders.show', $this->order->id));
    }
}
