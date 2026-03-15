# Event Importer Module

Pulls events from external platforms (Eventbrite, Meetup, Luma) into the HackGreenville database on a schedule.

## Commands
- `php artisan import:events` — import events (runs hourly in production)
- `php artisan events:prune` — remove events deleted from source platforms (runs daily at 02:00)

## Testing
- Run module tests: `./vendor/bin/phpunit app-modules/event-importer/tests/`
- Tests use `Http::fake()` with JSON fixtures in `tests/fixtures/`
- Base class: `Tests\DatabaseTestCase` (SQLite in-memory, RefreshDatabase)
- Each handler has its own test class and fixture directory

## Architecture Decisions
- **Template Method pattern**: `AbstractEventHandler` defines the flow; each handler implements `mapIntoEventData()`, `mapIntoVenueData()`, and `eventResults()`
- **Spatie LaravelData DTOs**: `EventData` and `VenueData` are immutable — venue is not resolved to a DB model until `ImportEventForOrganization::process()` calls `resolveVenue()`
- **Deduplication**: Events keyed by `(service, service_id)` composite key with MD5 `event_uuid`. If a source platform changes an event's internal ID, it creates a duplicate.
- **Handler factory**: `Org::getEventHandler()` maps the org's `service` enum to a handler class via `config/event-import-handlers.php`

## Gotchas
- **MeetupGraphqlHandler is deprecated** — `MeetupGraphqlExtHandler` (v2 `/gql-ext` endpoint) is the active one. The v1 handler remains in code but is not in the active_services config.
- **Pagination varies per handler**: Eventbrite uses page count, Meetup REST uses Link headers, Meetup GraphQL uses cursors, Luma has no pagination. The `page_count` property is set as a side effect in `eventResults()`.
- **Meetup REST has no `end_at`** — defaults to start_at + 2 hours
- **Luma address parsing is fragile** — manually splits Google-style comma-separated addresses. Returns null venue on parse failure.
- **EventBrite venue caching**: Venue lookups cached 1 hour to avoid N+1 API calls (other handlers get venue data inline)
- **Prune command uses `sleep(1)`** between HTTP checks to avoid rate limiting source APIs
- **Online events**: Each handler detects these differently (string match, enum check, boolean flag) — all skip venue creation

## Environment Variables
- `EVENT_IMPORTER_MAX_DAYS_IN_PAST` (default: 30)
- `EVENT_IMPORTER_MAX_DAYS_IN_FUTURE` (default: 180)
- `EVENT_IMPORTER_MEETUP_GRAPHQL_PRIVATE_KEY` — PEM key as env var (alternative to file path)
- `EVENT_IMPORTER_MEETUP_GRAPHQL_PRIVATE_KEY_PATH` — path to PEM file
- `EVENT_IMPORTER_MEETUP_GRAPHQL_CLIENT_ID`, `_MEMBER_ID`, `_PRIVATE_KEY_ID` — Meetup OAuth2 JWT credentials
- `EVENT_DEBUG_LOGGING_ENABLED` — verbose logging to `storage/logs/meetup-graphql.log`
- EventBrite token via `config/services.php` → `services.eventbrite.private_token`

## Adding a New Event Source
1. Create a handler extending `AbstractEventHandler` in `src/Services/`
2. Add a case to the `EventServices` enum in `app/Enums/EventServices.php`
3. Register handler in `config/event-import-handlers.php` (both `handlers` and `active_services`)
4. Add fixtures and a feature test following existing handler test patterns
