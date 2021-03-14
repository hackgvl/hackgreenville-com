<?php

namespace App\Http\Controllers;

//use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class CalendarController extends Controller
{

    public function index()
    {
        return view('calendar.index');
    }
}
