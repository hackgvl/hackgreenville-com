<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ContactMessage;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function contact()
    {
        return view('contact.contact');
    }

    /**
     * Submits a new contact entry
     *
     * @param  \Illuminate\Http\ContactMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function submit(ContactMessageRequest $request)
    {
        $validated = $request->validated();

        Notification::route('slack', config('services.slack.contact.webhook'))
            ->notify(new ContactMessage($validated['name'], $validated['contact'], $validated['message']));

        return view('contact.submitted');
    }
}
