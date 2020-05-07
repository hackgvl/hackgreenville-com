<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     *
     * @return View
     */
    public function index()
    {
        return view('index');
    }

	/**
	 * Show the application about us page.
	 *
	 * @return View
	 */
	public function about()
	{
		return view('about');
	}

	public function testing()
	{
		return view('testing');
	}
}
