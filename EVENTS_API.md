# Interacting with the HackGreenville Events API

The _Events API_ can be used to build your own custom applications from the structured JSON data representing the [HackGreenville events](https://hackgreenville.com/events).

## Interactive API Explorer

Start with the [interactive API explorer](https://hackgreenville.com/docs/api) which:
* Documents the query parameters / filters
* allows you to use the "Try it out" button to generate API URLs
* allows you to use the "Send Request" button to execute the API call within the explorer
* shows sample JSON responses

We support two API versions, v1 and v0.


## Limitations and Gotchas
* The production / live website is cached and changes may take up to 4 hours to show due to the cache.
* The Events API's responses are controlled by [server .env variables](/CONTRIBUTING.md#environment-variables) that may limit the data available to calling / consuming applications.
* All timestamps are in UTC.  
* The event description fields may include HTML markup.  This application does not sanitize those fields and it's unclear if the upstream source should be trusted, so sanitize any output to avoid malicious cross-site scripting (XSS).
* Results are returned only in JSON format.
* Please do not hammer the APIs
* Contact the contributors at [HackGreenville Labs](https://hackgreenville.com/labs) via Slack #hg-labs channel with any questions.

## Contributor Notes

The following notes are specifically for contributors developing the _Events API_.

* Primary controller: app-modules/api/src/Http/Controllers/EventApiV0Controller.php
* Test fixtures: app-modules/event-importer/tests/fixtures)

### Luma

The import code for this service exists in app-modules/event-importer/src/Services/LumaHandler.php

The Luma events for each org using the service are pulled via an public Luma URLs that are used to render the browser pages.

### Meetup.com

The [Meetup GraphQL-ext API](https://www.meetup.com/api/guide/#graphQl-guide) is used to query events. This version replaces an earlier, depricated GraphQL version. 

This API requires a paid Meetup Pro account, the cost of which is covered by [RefactorGVL](https://refactorgvl.com/).

### Eventbrite
The import code for this service exists in app-modules/event-importer/src/Services/EventBriteHandler.php

* [Eventbrite's API requires creating a free API key](https://www.eventbrite.com/help/en-us/articles/849962/generate-an-api-key/).
* [Eventbrite API Docs](https://www.eventbrite.com/platform/api)
* [Examples of making requests to the Eventbrite API](https://github.com/hackgvl/hackgreenville-com/issues/217#issuecomment-802212633)
* [Example "events" response using a test Eventbrite API key](https://www.eventbriteapi.com/v3/events/10584525601/?token=BKKRDKVUVRC5WG4HAVLT)

## Kudos to Past Contributors
* Thanks to @Nunie123 for the initial development, and to @ramona-spence for sustaining the [previous Python implementation](https://github.com/hackgvl/events-api).
* Thanks to @bogdankharchenko for migrating the Python implementation to PHP / Laravel
