<?php

namespace App\Services;

class EventDataService
{
    private const EVENTS_INFO_DIR = '/data/hg_nights';

    /**
     * Parses HG Nights events data from all JSON files in resources/data/hg-nights
     * and places the parsed JSON arrays into an array.
     *
     * @return array<array<mixed>
     */
    public function eventsData()
    {
        $event_files = $this->gatherEventFiles();

        return array_map([$this, 'parseEvent'], $event_files);
    }

    /**
     * Finds all files in resources/data/hg-nights that are JSON files.
     *
     * @return array<string>
     */
    private function gatherEventFiles()
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
    private function parseEvent($event_file)
    {
        $jsonString = file_get_contents(
            resource_path() . self::EVENTS_INFO_DIR . '/' . $event_file
        );

        return json_decode($jsonString, true);
    }
}
