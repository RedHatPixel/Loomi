<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderReceived extends Notification
{
    use Queueable;

    /**
     * @param  string  $storeName
     * @param  string  $customerName
     * @param  array<int, array{name: string, quantity: int}>  $items
     */
    public function __construct(
        public Order $order,
        public string $storeName,
        public string $customerName,
        public array $items,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $productList = collect($this->items)
            ->take(3)
            ->map(fn ($i) => "{$i['name']} x{$i['quantity']}")
            ->implode(', ');

        if (count($this->items) > 3) {
            $productList .= ' and more';
        }

        return [
            'type'    => 'new_order',
            'title'   => 'New order received!',
            'message' => "{$this->customerName} ordered {$productList} at {$this->storeName}.",
            'link'    => route('seller.orders.index'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $productList = collect($this->items)
            ->map(fn ($i) => "{$i['name']} x{$i['quantity']}")
            ->implode("\n");

        return (new MailMessage)
            ->subject("New Order at {$this->storeName}")
            ->greeting("New order from {$this->customerName}!")
            ->line("A new order #{$this->order->id} has been placed at your store {$this->storeName}.")
            ->line("Customer: {$this->customerName}")
            ->line("---")
            ->line($productList)
            ->line("---")
            ->action('View Orders', route('seller.orders.index'))
            ->line('Review and process the order now.');
    }
}
