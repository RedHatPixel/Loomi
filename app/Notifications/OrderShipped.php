<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderShipped extends Notification
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
            'type'    => 'order_shipped',
            'title'   => 'Order shipped!',
            'message' => "Items from {$this->storeName} in order #{$this->order->id} are on the way!",
            'link'    => route('orders.show', $this->order->id),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Items from {$this->storeName} Shipped!")
            ->greeting("Your order is on the way!")
            ->line("Items from {$this->storeName} in order #{$this->order->id} have been shipped and are on their way to you.")
            ->action('Track Order', route('orders.show', $this->order->id));
    }
}
