<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class apiController extends Controller
{
    /**
     * Debug route - delete in production.
     */
    public function debug()
    {
        $events = getEvents();
        $orgs = getOrgs();
		
        dd($events);
		
        $event_organizers = array();

        foreach ($events as $event):
            if ( !in_array( $event->group_name , $event_organizers )):
                $event_organizers[] = $event->group_name;
            endif;
        endforeach;

        $org_names = array();
    
        foreach ($orgs as $org):
            $org_names[] = $org->title;
        endforeach;

        $test = getOrgTypeArray($orgs);
        $newThing = array();

        foreach ( $event_organizers as $name ):
            if (array_key_exists($name, $test)):
                $newThing[$name] = $test[$name];
            endif;
        endforeach;

        dd($newThing);
        // dd( $event_organizers, $org_names);
        // $event_organizers holds an array of unique groups hosting events
        // $org_names holds an array of known groups

        $test = array();
        $false_names = array();

        foreach ($event_organizers as $name):
            if ( in_array( $name , $org_names )):
                $test[$name] = true;
            else:
                $test[$name] = false;
            endif;
        endforeach;
    
        // dd($test, $org_names);
        // dd( $test, $false_names, $org_names);
        // dd(array_filter($test, function ($value) {return !$value;}), $org_names);
	
        // return 'Next user story testing...';
    } // end of debug()
	

    /**
     * Display a list of organizations.
     */
    public function showOrgs()
    {
        $orgs = getOrgs();
		
        return view( 'orgs' , [ 'orgs' => $orgs, ]);
    } // end of showOrgs()
	

    /**
    * Display a list of events.
    */
    public function showEvents()
    {
        $events = getEvents();

        // Needed when sorting by OrgType.
        // $orgs = getOrgs();
		
        // Sort the events by date.
        usort( $events , 'compareTime');
		
        $months = getEventMonths( $events );
		
        // Not currently needed, as only one OrgType hosts meetups.
        // $orgTypes = getOrgTypes( $orgs );
		
        if (isset($_GET['month'])) {
            $events = filterOnMonth( $events , $_GET['month']);
        }
    
        // Filters orgs by type. Not currently needed.    
        // if (isset($_GET['type'])):
        //   $events = filterOnType( $events , $orgs , $_GET['type']);
        // endif;
		
        return view( 'events' , [ 'events' => $events,
		                          'months' => $months,]);

        // Sends the OrgTypes to the view, but not currently needed.
        // return view( 'events' , [ 'events' => $events,
        //                           'months' => $months,
        //                           'orgTypes' => $orgTypes,]);
    } // end of showEvents()
}
