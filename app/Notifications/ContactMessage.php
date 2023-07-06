<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $name;
    public $contact;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @param  string $name
     * @param  string $contact
     * @param  string $message
     * @return ContactMessage
     */
    public function __construct($name, $contact, $message)
    {
        $this->name = $name;
        $this->contact = $contact;
        $this->message = $message;
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
        $message = $this->message;

        return (new SlackMessage)
            ->success()
            ->content('A new contact submission has been received')
            ->attachment(function ($attachment) use ($name, $contact, $message) {
                $attachment->title('Reply via email', url("mailto:{$contact}"))
                    ->fields([
                        'Name' => $name,
                        'Contact' => $contact,
                        'Message' => $message,
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
            'Message' => $this->message,
        ];
    }
}
