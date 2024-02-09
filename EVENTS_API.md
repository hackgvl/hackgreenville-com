# Interacting with the Events API

## URLs and Query String Parameters
By default, results are returned in JSON format.

* [Get all upcoming events](https://hackgreenville.com/api/v0/events) by calling _/api/v0/events_
* [Get events within a date range](https://hackgreenville.com/api/v0/events?start_date=2024-01-15&end_date=2024-02-01) by calling _/api/v0/events?start_date=2024-01-15&end_date=2024-02-01_
    * the API defaults to providing only upcoming meetings, unless a `start_date` and `end_date` are specified
    * the API may only reply with a limited number of days in the past, as defined in the API's server configuration
    * "US/Eastern" is assumed as the timezone when a date filter is provided
* [Get events with a specific organizations tag](https://hackgreenville.com/api/v0/events?tags=1) by calling _/api/v0/events?tags=1_ - "tags" are applied to an organization in the [organizations API](https://github.com/codeforgreenville/OpenData/blob/master/ORGANIZATIONS_API.md).  Currently, the organizations API only provides integer tag IDs, such as with this tag #1, representing OpenWorks hosted events, The format of the JSON that returns is:
* The query parameters can be combined, so you could [request only events for a specific tag, during a specific date range](https://hackgreenville.com/api/v0/events?tags=1&start_date=2024-01-15&end_date=2024-02-01), like _/api/v0/events?tags=1&start_date=2024-01-15&end_date=2024-02-01_

## Limitations and Gotchas
* The Events API's responses are controlled by [server .env variables](/CONTRIBUTING.md#environment-variables) that may limit the data available to calling / consuming applications. Contact [HackGreenville Labs](https://hackgreenville.com/labs) with any questions about these limits for the HackGreenville.com Events API endpoints referenced above.
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
These notes are specifically for those helping develop the _Events API_.

### Meetup.com
* Wayback Machine has copies of the old v2 REST API docs, which were removed and redirected when their [GraphQL API too over](https://github.com/hackgvl/hackgreenville-com/issues/212).
* [v2 REST API - GET /events](https://web.archive.org/web/20170709041824/http://www.meetup.com/meetup_api/docs/2/events/)
* [v2 REST API - GET /groups](https://web.archive.org/web/20170709041556/http://www.meetup.com/meetup_api/docs/2/groups/)
* Examples
  * [GET upcoming events for one group / org](https://api.meetup.com/hack-greenville/events?&sign=true&photo-host=public)
  * [GET past events for one group / org](https://api.meetup.com/hack-greenville/events?&sign=true&photo-host=public&status=past)

### Eventbrite
* [Eventbrite API Docs](http://developer.eventbrite.com/doc/)
* [Examples of making requests to the Eventbrite API](https://github.com/hackgvl/hackgreenville-com/issues/217#issuecomment-802212633)
* [Example "events" response using a test Eventbrite API key](https://www.eventbriteapi.com/v3/events/10584525601/?token=BKKRDKVUVRC5WG4HAVLT)

## Kudos to Past Contributors
* Thanks to @Nunie123 for the initial development, and to @ramona-spence for sustaining the [previous Python implementation](https://github.com/hackgvl/events-api).
* Thanks to @bogdankharchenko for migrating the Python implementation to PHP / Laravel
