<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Models\Event;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $events = Event::getActive()->take(10);

        return view('welcome', compact('events'));
    }
}
