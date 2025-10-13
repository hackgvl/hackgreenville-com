# Interacting with the HackGreenville Orgs API

The _Organizations API_ can be used to build your own custom applications from the structured JSON data representing the [HackGreenville organizations](https://hackgreenville.com/orgs).

## Interactive API Explorer

Start with the [*interactive API explorer*](https://hackgreenville.com/docs/api) which:
* Documents the query parameters / filters
* allows you to use the "Try it out" button to generate API URLs
* allows you to use the "Send Request" button to execute the API call within the explorer
* shows sample JSON responses

We support two API versions, v1 and v0.

## Limitations and Gotchas
* The production / live website is cached and changes may take up to 4 hours to show due to the cache.
* Tag IDs are not documented in the explorer tool, and require manual creating by the HG Labs volunteers. Ex. tag ID 1 is for events hosted at OpenWorks Coworking.
* Please do not hammer the APIs
* Contact the contributors at [HackGreenville Labs](https://hackgreenville.com/labs) via Slack #hg-labs channel with any questions.

## Contributor Notes
* For those looking to help develop the _Organizations API_, the code is located at _app-modules/api/src/Http/Controllers/OrgsApiV0Controller.php_.
* When viewing API's responses in a web browser, check the "Pretty Print" box in the browser to make it easy to read.

## Kudos to Past Contributors
* Thanks to @allella for the initial development on Drupal
* Thanks to @bogdankharchenko for migrating the Drupal implementation to PHP / Laravel
