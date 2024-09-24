<?php

namespace Tests\Feature;

use App\Notifications\JoinMessage;
use Illuminate\Support\Facades\Notification;
use Scyllaly\HCaptcha\Facades\HCaptcha;
use Tests\DatabaseTestCase;

class JoinSlackTest extends DatabaseTestCase
{
    public function test_a_user_can_submit_a_slack_request(): void
    {
        Notification::fake();

        HCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        $this->post(route('join-slack.submit'), [
            'name' => 'John Doe',
            'contact' => 'john@fake.email',
            'reason' => 'I love Greenville!',
            'url' => 'https://linkedin.com/in/not-a-bot',
            'rules' => 1,
            'h-captcha-response' => 1234,
        ])
            ->assertSessionHasNoErrors();

        Notification::assertSentTo(
            Notification::route('slack', config('services.slack.contact.webhook')),
            function (JoinMessage $notification, array $channels) {
                return $notification->contact === 'john@fake.email'
                    && $notification->name === 'John Doe'
                    && $notification->reason === 'I love Greenville!'
                    && $notification->url === 'https://linkedin.com/in/not-a-bot';
            }
        );
    }
}
