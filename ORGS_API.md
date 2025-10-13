# HackGreenville Orgs API

The _Organizations API_ can be used to build your own custom applications from the structured JSON data representing the [HackGreenville organizations](https://hackgreenville.com/orgs).

## Interactive API Explorer

Start with the [interactive API explorer](https://hackgreenville.com/docs/api) which:
* documents the query parameters that allow for filtering the results
* allows you to use a built-in "_Try it out_" button to generate API URLs
* allows you to use a built-in "_Send Request_" button to execute the API call within the explorer
* shows sample JSON responses

## Limitations and Gotchas
* We support two API versions, v1 and v0, but we encourage building or migrating to v1.
* The production / live website is cached and changes may take up to 4 hours to show due to the cache.
* Tag IDs are not documented in the API explorer and require manual creation by the HG Labs volunteers. Example: tag ID 1 is for events hosted at OpenWorks Coworking.
* Please do not hammer the APIs
* Contact the contributors at [HackGreenville Labs](https://hackgreenville.com/labs) via Slack #hg-labs channel with any questions.

## Contributor Notes
* For those looking to help develop the _Organizations API_, the code is located at _app-modules/api/src/Http/Controllers/OrgsApiV0Controller.php_.
* When viewing API's responses in a web browser, check the "Pretty Print" box in the browser to make it easy to read.

## Kudos to Past Contributors
* Thanks to @allella for the initial development on Drupal
* Thanks to @bogdankharchenko for migrating the Drupal implementation to PHP / Laravel
