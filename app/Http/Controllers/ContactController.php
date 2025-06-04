<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Notifications\ContactMessage;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function contact()
    {
        return Inertia::render('Contact/Index');
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

        return Inertia::render('Contact/Submitted');
    }
}
