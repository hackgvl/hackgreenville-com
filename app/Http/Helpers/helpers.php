<?php

/**
 * Retrieve event information from API
 */
function getEvents () {
  $event_url = 'https://nunes.online/api/gtc';
  $event_data = file_get_contents( $event_url );
  
  // Put the data into JSON format.
  $events = json_decode( $event_data );

  return $events;
}

/**
 * Retrieve event information from API in array format
 */
function getEventsArray () {
  $event_url = 'https://nunes.online/api/gtc';
  $event_data = file_get_contents( $event_url );
  
  // Put the data into JSON format.
  $events = json_decode( $event_data , true );
  
  return $events;
}

/**
 * Retrieve organization information from API
 */
function getOrgs () {
  $org_url = 'https://data.openupstate.org/rest/organizations?_format=json';
  $org_data = file_get_contents( $org_url );
  
  // Put the data into JSON format.
	$orgs = json_decode( $org_data );
	
	// Match event hosts with known orgs.
	$orgs = convertOrgNames( $orgs );
	
	return $orgs;
}

/**
 * Convert event timestamp into readable format for display
 * Use Carbon package for less DateTime headaches...
 */
use Carbon\Carbon;
function printTime ($date) {
  $displayTime = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', 
                                            $date, 
                                            'UTC');
                                            
  return $displayTime->tz(config('app.timezone'))->format('g:i A, D j M y');
}


/**
 * Add general org info for Greenville SC Makers @ Synergy Mill 
 * Currently unused.
 */
function addMissingOrgs ($orgs) {
  $newOrg = new StdClass();
  $newOrg->title = "";
  $newOrg->field_organization_type = "";
  
  $orgs[] = $newOrg;
  
  return $orgs;
}

/**
 * Transform organization names to match event hosts.
 */
function convertOrgNames ($orgs) {
  foreach ( $orgs as $org ):
    $title = $org->title;

    switch ($title) {
      case "HackGreenville":
        $title = "HackGreenville Community";
        break;
      case "GSP Developers Guild":
        $title = "Greenville Spartanburg Developers' Guild";
        break;
      case "Code For Greenville":
        $title = "Code for Greenville";
        break;
      case "Cocoaheads":
        $title = "Greenville Cocoaheads";
        break;
      case "Upstate Elixir":
        $title = "Upstate |> Elixir";
        break;
      case "Greenville SC WordPress Meetup Group":
        $title = "Greenville South Carolina WordPress Group";
        break;
      case "ACM - Association for Computing Machinery":
        $title = "ACM Greenville";
        break;
      case "Synergy Mill":
        $title = "Greenville SC Makers @ Synergy Mill";
        break;
    }
    
    $org->title = $title;
  endforeach;
  
  return $orgs;
}

/**
 * Build a Google calendar url from an event object.
 */
function build_cal_url( $event )
{
  $event_time = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', 
  $event->time);
  $start_time = $event_time->format('Ymd\THis\Z');
  // Assume event is two hours long...
  $event_time->add(new DateInterval('PT2H'));
  $end_time = $event_time->format('Ymd\THis\Z');
  
  $location = '';
  
  if (property_exists($event, 'venue') && $event->venue != NULL ):
    $location .= $event->venue->name . ', ';
    $location .= $event->venue->address . ', ';
    $location .= $event->venue->city . ', ';
    $location .= $event->venue->state;
  endif;
  
  $calendar_url = "http://www.google.com/calendar/event?action=TEMPLATE&";
  $calendar_url .= 'text=' . urlencode($event->event_name) . '&';
  $calendar_url .= "dates=$start_time/$end_time&";
  $calendar_url .= 'details=' . urlencode( strip_tags( $event->description )) . '&';
  $calendar_url .= 'location=' . urlencode( $location ) . '&';
  $calendar_url .= "trp=false&";
  
  return $calendar_url;
}

/**
 * Return an array of unique organizaion types.
 */
function getOrgTypes( $orgs )
{
  $result = array();
  
  foreach ( $orgs as $org ) :
		if ( !in_array( $org->field_organization_type , $result )):
			$result[] = $org->field_organization_type;
		endif;
  endforeach;
	
	return $result;
}

/**
 * Comparison function for sorting events by time.
 */
function compareTime( $a , $b ) 
{
	if ( $a->time == $b->time ):
		return 0;
	endif;
	
	return ( $a->time < $b->time ) ? -1 : 1;
}

/**
 * Return an array of months containing events.
 */
function getEventMonths( $events )
{
  $result = array();
  
  foreach ( $events as $event )
  {
    $event_month = \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', 
			              $event->time)->format('F Y');
							
    if ( !in_array( $event_month , $result ))
		{
			$result[] = $event_month;
		}
  }
  
  return $result;
}

/**
 * Return only the events that occur in the given month.
 */
function filterOnMonth ( $events , $month )
{
  $result = array();
  
  foreach ( $events as $event ):
    $event_month = \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', 
			              $event->time)->format('F Y');
							
    if ( $event_month == $month ):
			$result[] = $event;
		endif;
  endforeach;
  
  return $result;
}

/**
 * Return only the events hosted by an organization of the given type.
 */
function filterOnType ( $events , $orgs , $type ) {
  $result = array();
  $orgTypeArray = getOrgTypeArray( $orgs );
  
  foreach ( $events as $event ):
    $event_host = $event->group_name;
    $event_type = $orgTypeArray[$event_host];
							
    if ( $event_type == $type ):
			$result[] = $event;
		endif;
  endforeach;
  
  return $result;
}

/**
 * Return an associative array of org name => org type.
 */
function getOrgTypeArray ( $orgs )
{
  $result = array();
  
  foreach ( $orgs as $org ):
    $result[$org->title] = $org->field_organization_type;
  endforeach;
  
  return $result;
}

/**
 * Return a link to the org homepage.
 * If no homepage, return a link to the org description at data.openupstate
 */
function getOrgWebsite ( $org )
{
  if ($org->field_homepage == ""):
    $link = $org->path;
  else:
    $link = $org->field_homepage;
  endif;
  
  return $link;
}
