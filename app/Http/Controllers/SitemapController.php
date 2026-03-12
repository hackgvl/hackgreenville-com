<?php

namespace App\Http\Controllers;

use App\Models\Org;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $orgs = Cache::remember('sitemap_orgs', 3600, function () {
            return Org::query()
                ->whereNotNull('slug')
                ->select(['slug', 'updated_at'])
                ->get();
        });

        return response()
            ->view('sitemap', ['orgs' => $orgs])
            ->header('Content-Type', 'application/xml');
    }
}
