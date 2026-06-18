<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductCreated extends Notification
{
    use Queueable;

    public function __construct(public Product $product) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'product_created',
            'title'   => 'Product published!',
            'message' => "{$this->product->name} has been published in your store.",
            'link'    => route('seller.products.index'),
        ];
    }
}
