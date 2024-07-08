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

    /**
     * Show the application Code of Conduct page.
     *
     * @return View
     */
    public function code_of_conduct()
    {
        return view('code-of-conduct');
    }

    public function testing()
    {
        return view('testing');
    }
}
