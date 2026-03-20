<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class LabsController extends Controller
{
    public function index()
    {
        $remove_space = true;
        $contributors = Cache::get('github_contributors', collect());
        $projects = [
            [
                'name' => __('HackGreenville.com'),
                'description' => __('The community website for Greenville, SC tech events, organizations, and resources'),
                'link' => 'https://github.com/hackgvl/hackgreenville-com#readme',
                'linkType' => 'github',
                'status' => 'active',
                'children' => [
                    [
                        'name' => __('Event Importer'),
                        'description' => __('Aggregates events from Eventbrite, Meetup, Luma, and more'),
                        'link' => 'https://github.com/hackgvl/hackgreenville-com/tree/develop/app-modules/event-importer',
                        'linkType' => 'github',
                    ],
                    [
                        'name' => __('Slack Events Bot'),
                        'description' => __('Automated weekly tech event notifications in Slack'),
                        'link' => 'https://github.com/hackgvl/hackgreenville-com/tree/develop/app-modules/slack-events-bot',
                        'linkType' => 'github',
                    ],
                    [
                        'name' => __('Events API'),
                        'description' => __('Public API for tech event information'),
                        'link' => 'https://github.com/hackgvl/hackgreenville-com/blob/develop/EVENTS_API.md',
                        'linkType' => 'github',
                    ],
                    [
                        'name' => __('Organizations API'),
                        'description' => __('Public API for tech organization data'),
                        'link' => 'https://github.com/hackgvl/hackgreenville-com/blob/develop/ORGS_API.md',
                        'linkType' => 'github',
                    ],
                    [
                        'name' => __('Map Layers API'),
                        'description' => __('Public API for Upstate location data'),
                        'link' => 'https://github.com/hackgvl/hackgreenville-com/blob/develop/docs/MAP_LAYERS_API.md',
                        'linkType' => 'github',
                    ],
                ],
            ],
            [
                'name' => __('Open Map Data Multi Layers Demo'),
                'description' => __('A bookmarkable map displaying all layers from the Map Layers API'),
                'link' => 'https://hackgvl.github.io/open-map-data-multi-layers-demo/',
                'linkType' => 'website',
                'status' => 'active',
            ],
        ];

        return view('labs.index', compact('projects', 'contributors', 'remove_space'));
    }
}
