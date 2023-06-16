<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class JoinMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $name;
    public $contact;
    public $reason;

    /**
     * Create a new notification instance.
     *
     * @param  string $name
     * @param  string $contact
     * @param  string $reason
     */
    public function __construct($name, $contact, $reason)
    {
        $this->name = $name;
        $this->contact = $contact;
        $this->reason = $reason;
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
        $name = $this->name;
        $contact = $this->contact;
        $reason = $this->reason;

        return (new SlackMessage)
            ->success()
            ->content('A new sign-up submission has been received')
            ->attachment(function ($attachment) use ($name, $contact, $reason) {
                $attachment
                    ->fields([
                        'Name' => $name,
                        'Contact' => $contact,
                        'Reason' => $reason,
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
        ];
    }
}
