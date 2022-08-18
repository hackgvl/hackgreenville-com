<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventsController extends Controller
{
	public function index()
	{
		$months = Event::search()
			->latest('active_at')
			->get()
			->groupBy(function(Event $event) {
				return $event->active_at->format('F Y');
			});
		
		return view('events.index', compact('months'));
	}
}
