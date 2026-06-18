<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'welcome',
            'title'   => 'Welcome to Loomi!',
            'message' => 'Start exploring products and find your style. Create a store to start selling your own brand.',
            'link'    => '/products',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Loomi!')
            ->greeting('Welcome to Loomi!')
            ->line('We are thrilled to have you on board. Start exploring products and find your style.')
            ->action('Browse Products', url('/products'))
            ->line('If you have a brand, you can create a store and start selling too!');
    }
}
