<?php

namespace App\Http\Controllers;

use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        return view('index', [
            'upcoming_events' => Event::future()
                ->published()
                ->with('organization', 'venue')
                ->oldest('active_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
