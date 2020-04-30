<?php

namespace App\Http\Controllers;

use App\Http\SearchPipeline\Active;
use App\Http\SearchPipeline\Month;
use App\Models\Event;
use Illuminate\Routing\Pipeline;

class EventsController extends Controller
{
	public function index()
	{
		$events = app(Pipeline::class)
			->send(Event::query())
			->through([
				// Get the active events
				Active::class,
				Month::class,
			])
			->thenReturn()
			->get();

		// Collect unique months format Oct 2019 from the events.
		$months = $events
			->map(function (Event $event) {
				if (!$event->active_at) {
					return false;
				}

				return $event->active_at->format('M Y');
			})->filter(function ($month) {
				// Filter out months
				return !!$month;
			})
			->unique();

		return view('events.index', compact('events', 'months'));
	}
}
