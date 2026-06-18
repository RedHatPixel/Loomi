<?php

namespace App\Notifications;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreApproved extends Notification
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
            'type'    => 'store_approved',
            'title'   => 'Store approved!',
            'message' => "Your store {$this->store->name} has been approved and is now open for business.",
            'link'    => route('seller.dashboard'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("{$this->store->name} is Approved!")
            ->greeting("Store approved!")
            ->line("Your store {$this->store->name} has been approved by our team.")
            ->action('Go to Dashboard', route('seller.dashboard'))
            ->line('Start adding products and serving customers!');
    }
}
