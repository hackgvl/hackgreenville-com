<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StyleController extends Controller
{
    public function index()
    {
        return view('styleguide.index');
    }
}
