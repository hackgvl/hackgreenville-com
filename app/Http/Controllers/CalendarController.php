<?php

namespace App\Http\Controllers;

use App\Contracts\CalendarContract;
use App\Http\Clients\GoogleCalendar;
use App\Models\Event;

//use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class CalendarController extends Controller
{

    public function index()
    {
	    return view('calendar.index');
    }
}
