# Interacting with the Events API

By default, results are returned in JSON format.  If an `Accept: application/json+ld` header is sent to the API, then it will reply with [Schema.org Event markup](https://schema.org/Event) in JSON+LD format.

* [Get all upcoming events](https://hackgreenville.com/api/v0/events) by calling _/api/v0/events_
* [Get events within a date range](https://hackgreenville.com/api/v0/events?start_date=2024-01-15&end_date=2024-02-01) by calling _/api/v0/events?start_date=2024-01-15&end_date=2024-02-01_
    * the API defaults to providing only upcoming meetings, unless a `start_date` and `end_date` are specified
    * "past events" are limited by the `[past_events] max_days_in_the_past` set in the config.ini file
    * "US/Eastern" is assumed as the timezone when a date filter is provided
* [Get events with a specific organizations tag](https://hackgreenville.com/api/v0/events?tags=1) by calling _/api/v0/events?tags=1_ - "tags" are applied to an organization in the [organizations API](https://github.com/hackgvl/OpenData/issues/17).  Currently, the organizations API only provides integer tag IDs, such as with this tag #1, representing OpenWorks hosted events, The format of the JSON that returns is:
* The query parameters can be combined, so you could [request only events for a specific tag, during a specific date range](https://hackgreenville.com/api/v0/events?tags=1&start_date=2024-01-15&end_date=2024-02-01), like _/api/v0/events?tags=1&start_date=2024-01-15&end_date=2024-02-01_

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

Note:
* All timestamps are in UTC.  
* The event description fields may include HTML markup.  This application does not sanitize those fields and it's unclear if the upstream source should be trusted, so sanitize any output to avoid malicious XSS.
* Kudos to @Nunie123 for the initial development and @ramona-spence for sustaining the [earlier Python repository](https://github.com/hackgvl/events-api).

