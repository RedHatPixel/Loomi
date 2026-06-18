<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'order_placed',
            'title'   => 'Order placed!',
            'message' => "Your order #{$this->order->id} has been placed successfully.",
            'link'    => route('orders.show', $this->order->id),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order #{$this->order->id} Placed")
            ->greeting("Order #{$this->order->id} Placed!")
            ->line('Your order has been placed successfully.')
            ->line("Total: ₱{$this->order->total}")
            ->action('View Order', route('orders.show', $this->order->id));
    }
}
