<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Notifications\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function contact(): View
    {
        return view('contact.contact');
    }

    public function submit(ContactMessageRequest $request): View
    {
        $validated = $request->validated();

        Notification::route('slack', config('services.slack.contact.webhook'))
            ->notify(new ContactMessage($validated['name'], $validated['contact'], $validated['message']));

        return view('contact.submitted');
    }
}
