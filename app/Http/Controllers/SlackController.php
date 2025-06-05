<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinMessageRequest;
use App\Notifications\JoinMessage;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Inertia\Response;

class SlackController extends Controller
{
    public function join(): Response
    {
        return Inertia::render('Slack/Index');
    }

    /**
     * Submits a new request to join slack
     *
     * @param  JoinMessageRequest  $request
     * @return Response
     */
    public function submit(JoinMessageRequest $request): Response
    {
        Notification::route('slack', config('services.slack.contact.webhook'))
            ->notify(new JoinMessage(
                $request->validated('name'),
                $request->validated('contact'),
                $request->validated('reason'),
                $request->validated('url')
            ));

        return Inertia::render('Slack/Submitted');
    }
}
