<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class GiveController extends Controller
{
    public function index(): View
    {
        return view('give');
    }
}
