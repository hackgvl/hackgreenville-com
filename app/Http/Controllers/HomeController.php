<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'upcoming_events' => Event::future()
                ->published()
                ->with('organization')
                ->limit(5)
                ->get(),
        ]);
    }
}
