<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ContactMessage;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function contact()
    {
        return view('contact');
    }

    /**
     * Submits a new contact entry
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'contact' => 'required|email:rfc,dns',
                'message' => 'required|max:5000',
                'h-captcha-response' => 'required|HCaptcha'
            ],
            [
                'h-captcha-response.required' => __('Please verify that you are not a robot.'),
                'h-captcha-response.captcha' => __('Captcha error! try again later or contact site admin.'),
            ],
        );

        if ($validator->fails()) {
            return redirect('contact')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        Notification::route('slack', config('services.slack.contact.webhook'))
            ->notify(new ContactMessage($validated['name'], $validated['contact'], $validated['message']));

        return view('contact.submitted');
    }
}
