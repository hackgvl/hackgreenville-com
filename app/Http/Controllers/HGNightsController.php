<?php

namespace App\Http\Controllers;

use App\Services\EventDataService;
use Illuminate\View\View;

class HGNightsController extends Controller
{
    private $eventDataService;

    public function __construct(EventDataService $eventDataService)
    {
        $this->eventDataService = $eventDataService;
    }

    /**
     * Present the HackGreenville Nights Page
     *
     * @return View
     */
    public function index()
    {
        return view('hg-nights.index')->with('events', $this->eventDataService->eventsData());
    }
}
