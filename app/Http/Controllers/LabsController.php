<?php

namespace App\Http\Controllers;

class LabsController extends Controller
{
    public function index()
    {
        $remove_space = true;
        $projects = [
            [
                'name' => __('Events API'),
                'description' => __('Public API for tech event information'),
                'link' => 'https://github.com/hackgvl/OpenData',
                'linkType' => 'github',
                'status' => 'active',
            ],
            [
                'name' => __('Map Layers API'),
                'description' => __('Public API for Upstate location data'),
                'link' => 'https://github.com/hackgvl/OpenData/blob/master/MAPS_API.md',
                'linkType' => 'github',
                'status' => 'active',
            ],
            [
                'name' => __('HackGreenville.com'),
                'description' => __('Tech events in Greenville, SC'),
                'link' => 'https://github.com/hackgvl/hackgreenville-com#readme',
                'linkType' => 'github',
                'status' => 'active',
            ],
            [
                'name' => __('HackGreenville Slack Events Bot'),
                'description' => __('Automated weekly tech event notifications in Slack'),
                'link' => 'https://github.com/hackgvl/slack-events-bot',
                'linkType' => 'github',
                'status' => 'active',
            ],
            [
                'name' => __('Open Map Data Multi Layers Demo'),
                'description' => __('A bookmarkable map displaying all layers from the Map Layers API'),
                'link' => 'https://hackgvl.github.io/open-map-data-multi-layers-demo/',
                'linkType' => 'website',
                'status' => 'active',
            ],
        ];

        return view('labs.index', compact('projects', 'remove_space'));
    }
}
