<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\NewUserWelcome;
use Illuminate\Support\Facades\Mail;

class EmailNewUser
{
    /**
     * Handle the event.
     */
    public function handle(UserCreated $created): void
    {
        Mail::send(new NewUserWelcome($created->user));
    }
}
