<?php

use Carbon\Carbon;

/**
 * Retrieve event information from API
 */
function getEvents()
{
    $event_url  = config('app.events_api_domain') . '/api/gtc';
    $event_data = file_get_contents($event_url);

    // Put the data into JSON format.
    $events = json_decode($event_data);

    // loop through all events and add a local time using the apps timezone
    foreach ($events as $event) :
        $displayTime = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $event->time, 'UTC');
        // store a local time so we don't have to do this conversion on every view
        $event->localtime = $displayTime->tz(config('app.timezone'));
    endforeach;

    usort($events, 'compareTime');

    return $events;
}

/**
 * Retrieve event information from API in array format
 */
function getEventsArray()
{
    $event_data = getEvents();

    // Put the data into JSON format.
    $events = json_decode($event_data, true);

    return $events;
}

/**
 * Retrieve active organization information from API
 */
function getActiveOrgs()
{
    $org_url  = config('app.orgs_api_domain') . '/rest/organizations?_format=json&org_status=active';
    $org_data = file_get_contents($org_url);

    // Put the data into JSON format.
    $orgs = json_decode($org_data);

    foreach ($orgs as $org) :
        $groupedOrgs[$org->field_organization_type][] = $org;
    endforeach;

    return $groupedOrgs;
}

/**
 * Retrieve inactive organization information from API
 */
function getInactiveOrgs()
{
    $org_url  = config('app.orgs_api_domain') . '/rest/organizations?_format=json&org_status=inactive';
    $org_data = file_get_contents($org_url);

    // Put the data into JSON format.
    $orgs = json_decode($org_data);

    return $orgs;
}


/**
 * Build a Google calendar url from an event object.
 */
function build_cal_url($event)
{
    $event_time = DateTime::createFromFormat('Y-m-d\TH:i:s\Z',
        $event->time);

    $start_time = $event_time->format('Ymd\THis\Z');

    // Assume event is two hours long...
    $event_time->add(new DateInterval('PT2H'));
    $end_time = $event_time->format('Ymd\THis\Z');

    $location = '';

    if (property_exists($event, 'venue') && $event->venue != null):
        $location .= $event->venue->name . ', ';
        $location .= $event->venue->address . ', ';
        $location .= $event->venue->city . ', ';
        $location .= $event->venue->state;
    endif;

    $calendar_url = "http://www.google.com/calendar/event?action=TEMPLATE&";
    $calendar_url .= 'text=' . urlencode($event->event_name) . '&';
    $calendar_url .= "dates=$start_time/$end_time&";
    $calendar_url .= 'details=' . urlencode(strip_tags($event->description)) . '&';
    $calendar_url .= 'location=' . urlencode($location) . '&';
    $calendar_url .= "trp=false&";

    return $calendar_url;
}

/**
 * Return an array of unique organizaion types.
 */
function getOrgTypes($orgs)
{
    $result = [];

    foreach ($orgs as $org) :
        if (!in_array($org->field_organization_type, $result)):
            $result[] = $org->field_organization_type;
        endif;
    endforeach;

    return $result;
}

/**
 * Comparison function for sorting events by time.
 */
function compareTime($a, $b)
{
    if ($a->time == $b->time):
        return 0;
    endif;

    return ($a->time < $b->time) ? -1 : 1;
}

/**
 * Return an array of months containing events.
 * @param $events
 * @return array
 */
function getEventMonths($events)
{
    $result = [];

    foreach ($events as $event) {
        $event_month = DateTime::createFromFormat('Y-m-d\TH:i:s\Z',
            $event->time)->format('F Y');

        if (!in_array($event_month, $result)) {
            $result[] = $event_month;
        }
    }

    return $result;
}

/**
 * Return only the events that occur in the given month.
 * @param $events
 * @param $month
 * @return array
 */
function filterOnMonth($events, $month)
{
    $result = [];

    foreach ($events as $event):
        $event_month = DateTime::createFromFormat('Y-m-d\TH:i:s\Z',
            $event->time)->format('F Y');

        if ($event_month == $month):
            $result[] = $event;
        endif;
    endforeach;

    return $result;
}

/**
 * Return only the events hosted by an organization of the given type.
 * @param $events
 * @param $orgs
 * @param $type
 * @return array
 */
function filterOnType($events, $orgs, $type)
{
    $result       = [];
    $orgTypeArray = getOrgTypeArray($orgs);

    foreach ($events as $event):
        $event_host = $event->group_name;
        $event_type = $orgTypeArray[$event_host];

        if ($event_type == $type):
            $result[] = $event;
        endif;
    endforeach;

    return $result;
}

/**
 * Return an associative array of org name => org type.
 * @param $orgs
 * @return array
 */
function getOrgTypeArray($orgs)
{
    $result = [];

    foreach ($orgs as $org):
        $result[$org->title] = $org->field_organization_type;
    endforeach;

    return $result;
}

/**
 * Return a link to the org homepage.
 * If no homepage, return a link to the org description at data.openupstate
 * @param $org
 * @return mixed
 */
function getOrgWebsite($org)
{
    return $org->field_homepage == '' ? $org->path : $org->field_homepage;
}
