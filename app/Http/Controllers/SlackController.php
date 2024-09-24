<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinMessageRequest;
use App\Notifications\JoinMessage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
     * @param  JoinMessageRequest  $request
     * @return Application|Factory|View
     */
    public function submit(JoinMessageRequest $request)
    {
        Notification::route('slack', config('services.slack.contact.webhook'))
            ->notify(new JoinMessage(...$request->safe(['name', 'contact', 'reason', 'url'])));

        return view('slack.submitted');
    }
}
