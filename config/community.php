<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Community Stats
    |--------------------------------------------------------------------------
    |
    | Manually maintained community numbers shown in the homepage hero. The
    | organization and event counts are computed from the database; these
    | two have no data source in the app, so update them as the community
    | grows.
    |
    */

    'slack_members' => (int) env('COMMUNITY_SLACK_MEMBERS', 2000),

    'active_individuals' => (int) env('COMMUNITY_ACTIVE_INDIVIDUALS', 200),

];
