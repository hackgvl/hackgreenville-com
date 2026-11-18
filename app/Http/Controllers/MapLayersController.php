<?php

namespace App\Http\Controllers;

use App\Models\MapLayer;

class MapLayersController extends Controller
{
    public function index()
    {
        $layers = MapLayer::orderBy('title')->get();

        return view('map-layers.index', compact('layers'));
    }
}
