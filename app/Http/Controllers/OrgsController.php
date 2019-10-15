<?php

namespace App\Http\Controllers;

use App\Models\Org;

class OrgsController extends Controller
{
    public function index()
    {
        $activeOrgs   = Org::with('category')->get()->groupBy('category_id');
        $inactiveOrgs = Org::with('category')->onlyTrashed()->get();

        return view('orgs.index', compact('activeOrgs', 'inactiveOrgs'));
    }
}
