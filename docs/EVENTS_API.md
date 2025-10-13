# Interacting with the HackGreenville Events API

## Interactive API Explorer
You can view the interactive API explorer via https://hackgreenville.com/docs/api

## URLs and Query String Parameters
By default, results are returned in JSON format.

* [Get all upcoming events](https://hackgreenville.com/api/v0/events) by calling _/api/v0/events_
* [Get events within a date range](https://hackgreenville.com/api/v0/events?start_date=2024-01-15&end_date=2024-02-01) by calling _/api/v0/events?start_date=2024-01-15&end_date=2024-02-01_
    * the API defaults to providing only upcoming meetings, unless a `start_date` and `end_date` are specified
    * the API may only reply with a limited number of days in the past, as defined in the API's server configuration
    * "US/Eastern" is assumed as the timezone when a date filter is provided
* [Get events with a specific organizations tag](https://hackgreenville.com/api/v0/events?tags=1) by calling _/api/v0/events?tags=1_ - "tags" are applied to an organization in the [organizations API](https://github.com/hackgvl/hackgreenville-com/blob/develop/ORGS_API.md).  Currently, the organizations API only provides integer tag IDs, such as with this tag #1, representing OpenWorks hosted events, The format of the JSON that returns is:
* The query parameters can be combined, so you could [request only events for a specific tag, during a specific date range](https://hackgreenville.com/api/v0/events?tags=1&start_date=2024-01-15&end_date=2024-02-01), like _/api/v0/events?tags=1&start_date=2024-01-15&end_date=2024-02-01_

## Limitations and Gotchas
* The Events API's responses are controlled by [server .env variables](/CONTRIBUTING.md#environment-variables) that may limit the data available to calling / consuming applications. Contact [HackGreenville Labs](https://hackgreenville.com/labs) with any questions about these limits for the HackGreenville.com Events API endpoints referenced above.
* The production / live website is cached and changes may take up to 4 hours to show due to the cache.
* All timestamps are in UTC.  
* The event description fields may include HTML markup.  This application does not sanitize those fields and it's unclear if the upstream source should be trusted, so sanitize any output to avoid malicious cross-site scripting (XSS).
* Previously, an `Accept: application/json+ld` header could be sent to the API to fetch [Schema.org Event markup](https://schema.org/Event) in JSON+LD format. However, this feature is not implemented in the newest version.

## Sample JSON Event Object Response

```
{
	"event_name":"Calm Rails Upgrades",
	"group_name":"Upstate Ruby",
	"group_url":"https:\/\/twitter.com\/upstateruby",
	"url":"https:\/\/www.meetup.com\/upstate-ruby\/events\/298465949\/",
	"time":"2024-01-25T23:30:00.000000Z",
	"tags":1,
	"status":"upcoming",
	"rsvp_count":7,
	"description":"<p>Upgrading a Rails app can be daunting and stressful. Make your next Rails upgrade calm and oddly satisfying.<\/p> ",
	"uuid":"5fe306da6dc0df14fb6c182229d3ebe6",
	"data_as_of":"2024-01-23T18:49:52.738311Z",
	"service_id":"298465949",
	"service":"meetup",
	"is_paid":false,
	"venue":{
		"name":"OpenWorks",
		"address":"101 N Main St #302",
		"city":"Greenville",
		"state":"SC",
		"zip":"29601",
		"country":"us",
		"lat":"34.852020263672",
		"lon":"-82.399681091309"
	},
	"created_at":"2024-01-11T21:37:20.000000Z"
}
```

## Contributor Notes
The following notes are specifically for contributors developing the _Events API_ at _app-modules/api/src/Http/Controllers/EventApiV0Controller.php_.

The _[test fixtures](https://github.com/hackgvl/hackgreenville-com/tree/develop/app-modules/event-importer/tests/fixtures)_ give an example of the responses one might expect from the remote APIs.

### Meetup.com
The [Meetup GraphQL API](https://www.meetup.com/api/schema/#graphQl-schema) is used to query events.  This API requires a paid Meetup Pro account, the cost of which is covered by [RefactorGVL](https://refactorgvl.com/).

The import code for this service exists in app-modules/event-importer/src/Services/MeetupRestHandler.php and schema of interest include [groupByUrlname](https://www.meetup.com/api/schema/#groupByUrlname) and [Event](https://www.meetup.com/api/schema/#Event).
* Wayback Machine has copies of the old v2 REST API docs, which were removed and redirected when their [GraphQL API took over](https://github.com/hackgvl/hackgreenville-com/issues/212).
* [v2 REST API - GET /events](https://web.archive.org/web/20170709041824/http://www.meetup.com/meetup_api/docs/2/events/)
* [v2 REST API - GET /groups](https://web.archive.org/web/20170709041556/http://www.meetup.com/meetup_api/docs/2/groups/)
* Examples
  * [GET upcoming events for one group / org](https://api.meetup.com/hack-greenville/events?&sign=true&photo-host=public&status=upcoming)
  * [GET past and cancelled events for one group / org](https://api.meetup.com/hack-greenville/events?&sign=true&photo-host=public&status=past,cancelled)
  * [GET all events after 2024-02-12 for one group / org](https://api.meetup.com/synergymill/events?&sign=true&photo-host=public&no_earlier_than=2024-02-12T02:21:20.000&status=upcoming,cancelled,past&page=50) where dates is of the format %Y-%m-%dT%H:%M:%S.000

### Eventbrite
The import code for this service exists in app-modules/event-importer/src/Services/EventBriteHandler.php

* [Eventbrite's API requires creating a free API key](https://www.eventbrite.com/help/en-us/articles/849962/generate-an-api-key/).
* [Eventbrite API Docs](https://www.eventbrite.com/platform/api)
* [Examples of making requests to the Eventbrite API](https://github.com/hackgvl/hackgreenville-com/issues/217#issuecomment-802212633)
* [Example "events" response using a test Eventbrite API key](https://www.eventbriteapi.com/v3/events/10584525601/?token=BKKRDKVUVRC5WG4HAVLT)

### Luma

The import code for this service exists in app-modules/event-importer/src/Services/LumaHandler.php

The Luma events for each org using the service are pulled via an public Luma URLs that are used to render the browser pages.

## Kudos to Past Contributors
* Thanks to @Nunie123 for the initial development, and to @ramona-spence for sustaining the [previous Python implementation](https://github.com/hackgvl/events-api).
* Thanks to @bogdankharchenko for migrating the Python implementation to PHP / Laravel

## Archive

### Meetup REST API

* Wayback Machine has copies of the old v2 REST API docs, which were removed and redirected when their [GraphQL API too over](https://github.com/hackgvl/hackgreenville-com/issues/212).
* [v2 REST API - GET /events](https://web.archive.org/web/20170709041824/http://www.meetup.com/meetup_api/docs/2/events/)
* [v2 REST API - GET /groups](https://web.archive.org/web/20170709041556/http://www.meetup.com/meetup_api/docs/2/groups/)
* Examples
  * [GET upcoming events for one group / org](https://api.meetup.com/hack-greenville/events?&sign=true&photo-host=public&status=upcoming)
  * [GET past and cancelled events for one group / org](https://api.meetup.com/hack-greenville/events?&sign=true&photo-host=public&status=past,cancelled)
  * [GET all events after 2024-02-12 for one group / org](https://api.meetup.com/synergymill/events?&sign=true&photo-host=public&no_earlier_than=2024-02-12T02:21:20.000&status=upcoming,cancelled,past&page=50) where dates is of the format %Y-%m-%dT%H:%M:%S.000
