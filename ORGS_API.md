# Interacting with the HackGreenville Orgs API

The _Organizations API_ can be used to build your own custom applications from the structured JSON data representing the [HackGreenville Organizations](https://hackgreenville.com/orgs).

## URLs and Query String Parameters

* [Show all organizations](https://hackgreenville.com/api/v0/orgs)
* [Filter to only organizations with a certain tag ID](https://hackgreenville.com/api/v0/orgs?tags=1)
  * Tag IDs are as defined by the admins at [HackGreenville Labs](https://hackgreenville.com/labs)
  * For example, ID 1 is the tag for events related to [OpenWorks Coworking](https://joinopenworks.com).

## Contributor Notes
* The production / live website is cached and changes may take up to 4 hours to show due to the cache.
* For those looking to help develop the _Organizations API_, the code is located at _app-modules/api/src/Http/Controllers/OrgsApiV0Controller.php_.
* When viewing the API's response JSON, it's best to use Firefox, or enable the "Pretty Print" option in your browser.
