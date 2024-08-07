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
                ->with('organization')
                ->limit(5)
                ->get(),
        ]);
    }
}
