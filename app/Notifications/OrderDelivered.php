<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDelivered extends Notification
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
            'type'    => 'order_delivered',
            'title'   => 'Order delivered!',
            'message' => "Items from {$this->storeName} in order #{$this->order->id} have been delivered.",
            'link'    => route('orders.show', $this->order->id),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Items from {$this->storeName} Delivered")
            ->greeting("Order delivered!")
            ->line("Items from {$this->storeName} in order #{$this->order->id} have been delivered. Enjoy!")
            ->action('View Order', route('orders.show', $this->order->id))
            ->line('Thank you for shopping with us!');
    }
}
