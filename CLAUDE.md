# HackGreenville.com

## Stack
- **Backend**: Laravel 12 (PHP 8.5+), Filament 3.3 admin
- **Database**: MySQL 8.0 (SQLite in-memory for tests)
- **Frontend**: Tailwind CSS 4, Alpine.js (lazy), Turbo Drive
- **Build**: Vite 7, Yarn, Node 22.12+
- **API**: Versioned REST (v0, v1), Scribe docs at `/docs/api`
- **Queue**: Redis driver, `slack` and `default` queues
- **Deploy**: Railway, Docker (Laravel Sail), GitHub Actions CI
- **Code Style**: Laravel Pint (PSR-12), Prettier for JS/CSS

## Project Structure
- `/app` — Core app (Models, Enums, Filament resources, Mail, Notifications)
- `/app-modules/api` — Versioned API controllers
- `/app-modules/event-importer` — Handlers for Eventbrite, Meetup, Luma, OpenUpstate
- `/app-modules/slack-events-bot` — Slack bot for event summaries
- `/resources/views` — Blade templates
- `/resources/js` — Vite entry points

## Scheduled Commands
- `ImportEventsCommand` — hourly
- `PruneMissingEventsCommand` — daily at 02:00

## Frontend Conventions
- **No jQuery, Lodash, or Moment.js** — use vanilla JS and native APIs
- **Modals**: Native `<dialog>` element, not libraries
- **Date formatting**: `Intl.DateTimeFormat` or FullCalendar's `formatRange()`
- **Code splitting**: Page-specific libraries are lazy-loaded, not bundled globally
  - `calendar-libs.js` — FullCalendar + CSS, loaded only on `/calendar`
  - Alpine.js — auto-loaded only when `[x-data]` is on the page
  - Global bundle (`app.js`) stays lean — only Turbo + site-wide JS
- Inline `<script>` in Blade must use `type="module"` to run after Vite bundle

## Testing & Validation
- After implementing features, run `./vendor/bin/pint` to fix code style and `php artisan test` to verify tests pass
- `yarn build` must pass cleanly after frontend changes
- PHPUnit 11, test suites: Feature, Unit, plus app-module tests
