# HackGreenville Events API

The _Events API_ can be used to build your own custom applications from the structured JSON data representing the [HackGreenville events](https://hackgreenville.com/events).

## Interactive API Explorer

Start with the [interactive API explorer](https://hackgreenville.com/docs/api) which:
* documents the query parameters that allow for filtering the results
* allows you to use a built-in "_Try it out_" button to generate API URLs
* allows you to use a built-in "_Send Request_" button to execute the API call within the explorer
* shows sample JSON responses

## Limitations, Gotchas, and Contributor Tips
* We support two API versions, v1 and v0, but we encourage using v1.
* The production / live website is cached and changes may take up to 4 hours to show due to the cache.
* The Events API's responses are controlled by [server .env variables](/CONTRIBUTING.md#environment-variables) that may limit the data available to calling / consuming applications.
* All timestamps are in UTC.  
* The event description fields may include HTML markup.  This application does not sanitize those fields and it's unclear if the upstream source should be trusted, so sanitize any output to avoid malicious cross-site scripting (XSS).
* Results are returned only in JSON format.
* Please do not hammer the APIs
* Contact the contributors at [HackGreenville Labs](https://hackgreenville.com/labs) via Slack #hg-labs channel with any questions.

## The Code

The code for the Events API is primarily located in _/app-modules/_

* Controllers: _api/src/Http/Controllers/_
* Resources: _api/src/Resources/_
* Test fixtures: _api/tests/Feature/_

### Luma.com

The Luma events are pulled via public calendar URLs for each organizations.
* These URLs are primarily used to render the calendar on the public organization pages and not part of the offical API
* The official API, which requires a paid account, is limited to events for ones own organization
* The public organization webpages also include JSON+LD markup if access to the public calendar URL were to cease.

The code for this service includes the following _/app-modules/event-importer/_
* _src/Services/LumaHandler.php_
* _tests/Feature/LumaTest.php_
* _tests/fixtures/luma/_

### Meetup.com

The [Meetup GraphQL-ext API](https://www.meetup.com/api/guide/#graphQl-guide) is used to query events. This version replaces an earlier, deprecated GraphQL version. 

This API requires a paid Meetup Pro account, the cost of which is covered by [RefactorGVL](https://refactorgvl.com/).

The code for this service includes the following _/app-modules/event-importer/_
* _src/Services/MeetupGraphqlExtHandler.php_
* _tests/Feature/MeetupGraphqlExtTest.php_
* _tests/fixtures/meetup-graphql/_
* Note: _Ext_ is the latest Meetup API, while _MeetupRest_ & _MeetupGraphql_ are legacy / deprecated APIs we have yet to delete.

### Eventbrite.com

The free Eventbrite API is used to syndicate the events for each organization using the service. 

* [Eventbrite's API requires creating a free API key](https://www.eventbrite.com/help/en-us/articles/849962/generate-an-api-key/).
* [Eventbrite API Docs](https://www.eventbrite.com/platform/api)
* [Examples of making requests to the Eventbrite API](https://github.com/hackgvl/hackgreenville-com/issues/217#issuecomment-802212633)
* [Example "events" response using a test Eventbrite API key](https://www.eventbriteapi.com/v3/events/10584525601/?token=BKKRDKVUVRC5WG4HAVLT)

The code for this service includes the following _/app-modules/event-importer/_
* _src/Services/EventBriteHandler.php_
* _tests/Feature/EventBriteTest.php_
* _tests/fixtures/eventbirte/_


## Kudos to Past Contributors
* Thanks to @Nunie123 for the initial development, and to @ramona-spence for sustaining the [previous Python implementation](https://github.com/hackgvl/events-api).
* Thanks to @bogdankharchenko for migrating the Python implementation to PHP / Laravel
