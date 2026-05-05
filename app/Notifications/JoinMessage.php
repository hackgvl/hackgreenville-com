<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class JoinMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  string $name
     * @param  string $contact
     * @param  string $reason
     * @param  ?string $url
     */
    public function __construct(
        public string $name,
        public string $contact,
        public string $reason,
        public ?string $url
    ) {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content(':traffic_light: Join HG Slack Request :traffic_light:')
            ->attachment(function ($attachment) {
                $attachment
                    ->fields([
                        'Name' => $this->name,
                        'Email to Invite' => $this->contact,
                        'Reason' => $this->reason,
                        'URL' => $this->url,
                    ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'Name' => $this->name,
            'Contact' => $this->contact,
            'Reason' => $this->reason,
            'Url' => $this->url,
        ];
    }
}
