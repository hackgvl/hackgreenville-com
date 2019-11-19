<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        return view('index');
    }
}
