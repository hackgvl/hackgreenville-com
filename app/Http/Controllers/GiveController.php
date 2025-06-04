<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class GiveController extends Controller
{
    public function index()
    {
        return Inertia::render('Give');
    }
}
