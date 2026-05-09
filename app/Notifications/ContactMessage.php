<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $name,
        public string $contact,
        public string $message,
    ) {
    }

    public function via($notifiable): array
    {
        return ['slack'];
    }

    public function toSlack($notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->success()
            ->content(':mailbox_with_mail: HG Website Contact Form Request :mailbox_with_mail:')
            ->attachment(function ($attachment) {
                $attachment->title('Reply via email', url("mailto:{$this->contact}"))
                    ->fields([
                        'Name' => $this->name,
                        'Email' => $this->contact,
                        'Message' => $this->message,
                    ]);
            });
    }

    public function toArray($notifiable): array
    {
        return [
            'Name' => $this->name,
            'Contact' => $this->contact,
            'Message' => $this->message,
        ];
    }
}
