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

    /**
     * Parses HG Nights events data from all JSON files in resources/data/hg-nights
     * and places the parsed JSON arrays into an array.
     *
     * @return array<array<mixed>
     */
    protected function events_data()
    {
        $event_files = self::gather_event_files();

        return array_map([$this, 'parse_event'], $event_files);
    }

    /**
     * Finds all files in resources/data/hg-nights that are JSON files.
     *
     * @return array<string>
     */
    private function gather_event_files()
    {
        return preg_grep(
            '~\.json$~',
            scandir(resource_path() . self::EVENTS_INFO_DIR, SCANDIR_SORT_DESCENDING)
        );
    }

    /**
     * Parses the string contents of a JSON file into an array.
     *
     * @return array<mixed>
     */
    private function parse_event($event_file)
    {
        $jsonString = file_get_contents(
            resource_path() . self::EVENTS_INFO_DIR . '/' . $event_file
        );

        return json_decode($jsonString, true);
    }
}
