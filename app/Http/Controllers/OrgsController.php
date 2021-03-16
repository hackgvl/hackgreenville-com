<?php

namespace App\Http\Controllers;

use App\Models\Org;

class OrgsController extends Controller
{
    public function index()
    {
        $activeOrgs = Org::with('category')->get()->groupBy('category_id');

        return view('orgs.index', compact('activeOrgs'));
    }

    public function inactive()
    {
        $inactiveOrgs = Org::with('category')->onlyTrashed()->get()->groupBy('category_id');

        return view('orgs.inactive', compact('inactiveOrgs'));
    }
}
