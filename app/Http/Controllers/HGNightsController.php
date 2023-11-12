<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HGNightsController extends Controller
{
    public const EVENTS_INFO_DIR = '/data/hg_nights';

    /**
     * Present the HackGreenville Nights Page
     *
     * @return View
     */
    public function index()
    {
        return view('hg-nights.index')->with('events', self::events_data());
    }

    protected function events_data()
    {
        $event_files = self::gather_event_files();

        return array_map([$this, 'parse_event'], $event_files);
    }

    private function gather_event_files()
    {
        return preg_grep(
            '~\.json$~',
            scandir(resource_path() . self::EVENTS_INFO_DIR, SCANDIR_SORT_DESCENDING)
        );
    }

    private function parse_event($event_file)
    {
        $jsonString = file_get_contents(
            resource_path() . self::EVENTS_INFO_DIR . '/' . $event_file
        );

        return json_decode($jsonString, true);
    }
}
