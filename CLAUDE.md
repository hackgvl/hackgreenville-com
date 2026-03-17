# HackGreenville.com

## Stack
- **Backend**: Laravel 12 (PHP ^8.5), Filament 3.3 admin panel
- **Database**: MySQL 8.0 (SQLite in-memory for tests)
- **Frontend**: Tailwind CSS 4, Alpine.js (lazy), Turbo Drive
- **Build**: Vite 7, Yarn, Node 22.12+
- **API**: Versioned REST (v0, v1), Scribe docs at `/docs/api`
- **Queue**: Redis driver, `slack` and `default` queues
- **Deploy**: Railway, Docker (Laravel Sail), GitHub Actions CI

## Development Commands
- `composer dev` — starts Laravel server + queue worker + Vite + log tailing (recommended)
- `yarn dev` — Vite dev server only
- `yarn build` — production frontend build
- `./vendor/bin/pint` — fix PHP code style (PSR-12)
- `yarn lint` — Prettier for JS/CSS
- `php artisan scribe:generate` — regenerate API docs

## Git Workflow
- Base branch: `develop` (all PRs target this)
- Branch naming: `feat/description`, `fix/description`, `docs/description`

## Modular Architecture
Self-contained modules live in `/app-modules/` and are auto-discovered via internachi/modular. Each module has its own CLAUDE.md with module-specific context:
- `app-modules/api` — versioned REST API
- `app-modules/event-importer` — imports events from Eventbrite, Meetup, Luma
- `app-modules/slack-events-bot` — posts event summaries to Slack

## Filament Admin
- Resources: Event, Category, Org, Tag, Venue (in `app/Filament/Resources/`)

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
- After implementing features, run `./vendor/bin/pint` then `php artisan test`
- `yarn build` must pass cleanly after frontend changes
- Prefer running single test files over the full suite for speed
- PHPUnit 11, test suites: Feature, Unit, plus app-module tests
