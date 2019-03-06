<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class apiController extends Controller
{
    /**
     * Display a list of organizations.
     */
    public function showOrgs()
    {
        $activeOrgs = getActiveOrgs();
        $inactiveOrgs = getInactiveOrgs();
		
        return view( 'orgs' , [ 'activeOrgs' => $activeOrgs, 'inactiveOrgs' => $inactiveOrgs ]);
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
