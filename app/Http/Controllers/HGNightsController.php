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
        return view('hg-nights.index');
    }
}
