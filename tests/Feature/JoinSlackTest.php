<?php

namespace Tests\Feature;

use App\Notifications\JoinMessage;
use Illuminate\Support\Facades\Notification;
use RyanChandler\LaravelCloudflareTurnstile\Facades\Turnstile;
use Tests\DatabaseTestCase;

class JoinSlackTest extends DatabaseTestCase
{
    public function test_a_user_can_submit_a_slack_request(): void
    {
        $this->withoutMiddleware();

        Notification::fake();
        Turnstile::fake();

        $this->post(route('join-slack.submit'), [
            'name' => 'John Doe',
            'contact' => 'john@fake.mailinator.com',
            'reason' => 'I love Greenville!',
            'url' => 'https://linkedin.com/in/not-a-bot',
            'rules' => 1,
            'cf-turnstile-response' => 'fake-token',
        ])
            ->assertSessionHasNoErrors();

        Notification::assertSentTo(
            Notification::route('slack', config('services.slack.contact.webhook')),
            fn (JoinMessage $notification, array $channels) => $notification->contact === 'john@fake.mailinator.com'
                    && $notification->name === 'John Doe'
                    && $notification->reason === 'I love Greenville!'
                    && $notification->url === 'https://linkedin.com/in/not-a-bot'
        );
    }
}
