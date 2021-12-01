<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public function __construct()
    {
       //
    }

    public function via($notifiable)
    {
        return [TelegramChannel::class, 'slack'];
        //return ['slack'];
    }

    public function toTelegram($notifiable)
    {
        $message = "Your card #: " . $notifiable->card_number . " " . $notifiable->status;
        $TELEGRAM_ID = $notifiable->user->channel_id();

        return TelegramMessage::create()
            ->to($TELEGRAM_ID)
            ->content($message);
    }

    public function toSlack($notifiable)
    {
        $message = "Your card #: " . $notifiable->card_number . " " . $notifiable->status;
        return (new SlackMessage)
            ->content($message);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
