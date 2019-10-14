<?php

namespace App\Http\Controllers;

class SlackController extends Controller
{
    public function join()
    {
        return view('slack-sign-up');
    }
}
