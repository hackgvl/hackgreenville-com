<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinMessageRequest;
use App\Notifications\JoinMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Notification;

class SlackController extends Controller
{
    public function join(): View
    {
        return view('slack.sign-up');
    }

    public function submit(JoinMessageRequest $request): View
    {
        Notification::route('slack', config('services.slack.contact.webhook'))
            ->notify(new JoinMessage(
                $request->validated('name'),
                $request->validated('contact'),
                $request->validated('reason'),
                $request->validated('url')
            ));

        return view('slack.submitted');
    }
}
