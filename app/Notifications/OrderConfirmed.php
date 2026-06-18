<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmed extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $storeName) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'order_confirmed',
            'title'   => 'Order confirmed',
            'message' => "Items from {$this->storeName} in order #{$this->order->id} have been confirmed.",
            'link'    => route('orders.show', $this->order->id),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Items from {$this->storeName} Confirmed")
            ->greeting("Order #{$this->order->id} — Items Confirmed!")
            ->line("Your items from {$this->storeName} have been confirmed by the seller.")
            ->action('View Order', route('orders.show', $this->order->id));
    }
}
