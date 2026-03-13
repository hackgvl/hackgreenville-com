<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HGNightsController extends Controller
{
    /**
     * Present the HackGreenville Nights Page
     *
     * @return View
     */
    public function index()
    {
        $events = require resource_path('data/hg-nights.php');

        return view('hg-nights.index', [
            'events' => $events,
        ]);
    }
}
