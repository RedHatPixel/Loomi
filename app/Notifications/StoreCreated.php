<?php

namespace App\Notifications;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreCreated extends Notification
{
    use Queueable;

    public function __construct(public Store $store) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'store_created',
            'title'   => 'Store created!',
            'message' => "Your store {$this->store->name} has been created successfully.",
            'link'    => route('seller.dashboard'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Your Store {$this->store->name} is Live!")
            ->greeting("Store created successfully!")
            ->line("Your store {$this->store->name} has been created and is now live.")
            ->action('Go to Dashboard', route('seller.dashboard'))
            ->line('Start adding products and growing your business!');
    }
}
