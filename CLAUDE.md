# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Architecture Overview

HackGreenville.com is a Laravel 10 + React/TypeScript application that serves the Greenville, SC tech community. It uses a modern stack with Inertia.js for SPA-like functionality while maintaining Laravel's server-side routing.

### Key Architectural Components

**Backend (Laravel)**
- **Controllers**: Follow RESTful patterns in `app/Http/Controllers/`. Each page has its own controller (HomeController, EventsController, etc.)
- **Models**: Located in `app/Models/` with relationships defined for Event, Org, Venue, Category, Tag, User
- **Event Import System**: Modular importers in `app-modules/event-importer/` handle data from external APIs (Meetup, Eventbrite, etc.)
- **Admin Panel**: Built with Filament v3 for content management
- **APIs**: RESTful endpoints documented in `EVENTS_API.md` and `ORGS_API.md`

**Frontend (React + TypeScript)**
- **Inertia.js Integration**: Server-side routing with client-side navigation
- **Component Structure**: Pages in `resources/js/pages/`, shared components in `resources/js/components/`
- **Styling**: Tailwind CSS v4 with custom CSS variables for theming
- **UI Library**: Radix UI components with custom variants using class-variance-authority
- **Calendar**: FullCalendar React integration for event display

**Routing Pattern**
- Laravel handles all routes in `routes/web.php` 
- Each route returns an Inertia page component
- API routes serve JSON data for calendar and external integrations

## Development Commands

### Frontend Development
```bash
# Start development server (includes Laravel + Vite HMR)
composer dev

# Build for production  
npm run build

# Development build with watch
npm run dev

# Lint JavaScript/TypeScript
npm run lint

# Lint PHP
npm run lint:php
```

### Backend Development
```bash
# Run Laravel development server only
php artisan serve

# Run tests
php artisan test

# Import events from external APIs
php artisan import:events

# Database migrations
php artisan migrate --seed

# Generate IDE helper files
composer generate-ide-helper

# Clear application cache
php artisan cache:clear
```

### Docker Development
```bash
# Start with Docker Compose
docker-compose -f docker-compose.local.yml up --build

# Execute commands in container
docker exec -it hackgreenville php artisan [command]
```

## Code Organization Patterns

### Event Data Flow
1. **Import**: External APIs → Event Importers → Database
2. **Display**: Database → Controllers → Inertia Pages → React Components
3. **Calendar**: Events API endpoint → FullCalendar React component

### Theme System
- CSS variables defined in `resources/css/app.css` for light/dark modes
- React context in `resources/js/contexts/ThemeContext.tsx` handles theme state
- Tailwind classes use CSS variables (e.g., `text-foreground`, `bg-background`)

### Component Patterns
- **Page Components**: Located in `resources/js/pages/[Section]/`
- **Shared UI**: `resources/js/components/ui/` contains reusable components
- **Layouts**: `AppLayout.tsx` wraps all pages with navigation and theme provider

## Key Configuration Files

- **Laravel Config**: Standard Laravel configs in `config/` with custom `event-import-handlers.php`
- **Frontend Build**: `vite.config.js` with React, path aliases, and image optimization
- **Styling**: `tailwind.config.js` with custom color system and content paths
- **TypeScript**: `tsconfig.json` with path mapping for `@/*` imports

## Environment Setup Notes

- Copy `.env.example` to `.env` for native development
- Copy `.env.docker` to `.env` for Docker development  
- Event importing requires API keys in environment variables
- Admin panel accessible after seeding (default: admin@admin.com/admin)
- Telescope debugging can be enabled with `TELESCOPE_ENABLED=TRUE`

## Testing & Quality

- **PHP Tests**: `php artisan test` runs PHPUnit tests
- **Code Style**: Laravel Pint for PHP, Prettier for JS/TS
- **Pre-commit Hooks**: Automatic linting on commit
- **IDE Support**: IDE helper files generated via Composer scripts

## External Integrations

- **Event Sources**: Meetup GraphQL API, Eventbrite, custom APIs
- **Calendar Export**: iCal format available at `/calendar-feed.ics`
- **Slack Integration**: Community signup flow with API integration
- **Admin Interface**: Filament-based CMS for content management