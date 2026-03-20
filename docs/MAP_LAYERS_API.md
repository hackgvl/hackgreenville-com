# HackGreenville Map Layers API

The _Map Layers API_ can be used to build your own custom applications from the structured JSON data representing the [HackGreenville map layers](https://hackgreenville.com/map-layers).

## Interactive API Explorer

Start with the [interactive API explorer](https://hackgreenville.com/docs/api) which:
* documents the query parameters that allow for filtering the results
* allows you to use a built-in "_Try it out_" button to generate API URLs
* allows you to use a built-in "_Send Request_" button to execute the API call within the explorer
* shows sample JSON responses

## Endpoints

* `GET /api/v1/map-layers` — list all map layers with pagination, filtering by title, and sorting
* `GET /api/v1/map-layers/{slug}/geojson` — fetch the raw GeoJSON FeatureCollection for a specific layer

## Limitations, Gotchas, and Contributor Tips
* The production / live website is cached and changes may take up to 4 hours to show due to the cache.
* GeoJSON data is synced from external sources (Google Sheets CSV or direct GeoJSON links) and cached locally.
* Please do not hammer the APIs
* Contact the contributors at [HackGreenville Labs](https://hackgreenville.com/labs) via Slack #hg-labs channel with any questions.

## The Code

The code for the Map Layers API is primarily located in _/app-modules/_

* Controllers: _api/src/Http/Controllers/_
* Resources: _api/src/Resources/MapLayers/_
* Tests: _api/tests/Feature/_
* Model: _app/Models/MapLayer.php_
* Sync Service: _app/Services/MapLayerSyncService.php_
