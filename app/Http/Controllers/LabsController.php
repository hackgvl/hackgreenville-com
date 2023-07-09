<?php

namespace App\Http\Controllers;

class LabsController extends Controller
{
    public function index()
    {
        $remove_space = true;
        $projects = [
            [
                'name' => __('Open Data Organizations API'),
                'description' => __('Public API for Open Data organization information'),
                'link' => 'https://github.com/hackgvl/OpenData',
                'linkType' => 'github',
                'status' => 'active',
            ],
            [
                'name' => __('Events API'),
                'description' => __('Public API for event information'),
                'link' => 'https://github.com/hackgvl/OpenData',
                'linkType' => 'github',
                'status' => 'active',
            ],
            [
                'name' => __('Open Data API and Map Layers'),
                'description' => __('Public API for Upstate location data'),
                'link' => 'https://github.com/hackgvl/OpenData/blob/master/MAPS_API.md',
                'linkType' => 'github',
                'status' => 'active',
            ],
            [
                'name' => __('HackGreenville'),
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
                'name' => __('Clear My Record'),
                'description' => __('Criminal record expungement'),
                'link' => 'https://medium.com/code-for-america/closing-the-delivery-gap-making-good-on-the-promise-of-californias-record-clearance-laws-5968b4b9278b',
                'linkType' => 'website',
                'status' => 'considering',
            ],
            [
                'name' => __('Integrated/Interactive Street Art'),
                'description' => __('Augumented reality art project'),
                'link' => 'https://github.com/hackgvl/public-art/issues',
                'linkType' => 'github',
                'status' => 'considering',
            ],
            [
                'name' => __('Greenville Codes'),
                'description' => __('Free, part-time coding education'),
                'link' => 'https://greenvillecodes.org/',
                'linkType' => 'website',
                'status' => 'considering',
            ],
            [
                'name' => __('Trolley Tracker Web App'),
                'description' => __('Live trolley tracking app (2014-2021)'),
                'link' => 'https://github.com/hackgvl/trolley-tracker',
                'linkType' => 'github',
                'status' => 'retired',
            ]
        ];

        return view('labs.index', compact('projects', 'remove_space'));
    }
}
