<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\JoinMessageRequest;
use Illuminate\Support\Facades\Validator;
use App\Notifications\JoinMessage;
use Illuminate\Support\Facades\Notification;

class SlackController extends Controller
{
    public function join()
    {
        return view('slack.sign-up');
    }

    /**
     * Submits a new request to join slack
     *
     * @param  \Illuminate\Http\JoinMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function submit(JoinMessageRequest $request)
    {
        $validated = $request->validated();

        Notification::route('slack', config('services.slack.contact.webhook'))
            ->notify(new JoinMessage($validated['name'], $validated['contact'], $validated['reason']));

        return view('slack.submitted');
    }
}
