<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HGNightsController extends Controller
{
    /**
     * Present the HackGreenville Nights Page
     */
    public function index()
    {
        return Inertia::render('HGNights/Index');
    }
}
