# HackGreenville.com Project Configuration

## Project Overview

This is the HackGreenville community website built with:
- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Bootstrap 4, jQuery, Sass
- **Database**: MySQL/SQLite
- **Admin Panel**: Filament 3.3
- **Build Tools**: Vite, Composer, Yarn
- **API**: Custom RESTful API with versioning (v0, v1)
- **Features**: Event management, organization directory, calendar feeds, Slack integration

The project follows a modular architecture with separate app-modules for API and event importing functionality.

## AI Development Team Configuration
*Configured by team-configurator on 2025-07-29*

Your project uses: Laravel 10, Bootstrap 4, MySQL, Filament Admin

### Specialist Assignments

#### Backend Development
- **Laravel Expert** → @laravel-backend-expert
  - Service layer implementation, command handlers
  - Repository patterns, dependency injection
  - Job queues, event listeners, notifications
  - Middleware, service providers, console commands

#### Database & ORM
- **Eloquent Specialist** → @laravel-eloquent-expert
  - Model relationships, query optimization
  - Database migrations, seeders, factories
  - Eloquent scopes, observers, and events
  - Performance tuning for large datasets

#### API Development
- **API Architect** → @api-architect
  - RESTful API design and versioning strategies
  - API resource transformations
  - Request validation and error handling
  - API documentation with Scribe

#### Frontend Development
- **Frontend Developer** → @frontend-developer
  - Bootstrap 4 components and layouts
  - jQuery interactions and AJAX
  - Sass/CSS architecture
  - Responsive design implementation

#### Code Quality
- **Code Reviewer** → @code-reviewer
  - Laravel best practices and conventions
  - PSR standards compliance
  - Security vulnerability detection
  - Performance bottleneck identification

- **Performance Optimizer** → @performance-optimizer
  - Query optimization and N+1 problem solving
  - Caching strategies (Redis, database, view)
  - Asset optimization and lazy loading
  - Database indexing recommendations

### How to Use Your Team

**For Backend Tasks:**
```
"Add a new feature to track event attendance"
"Implement email notifications for new events"
"Create a service to import events from Eventbrite"
```

**For Database Work:**
```
"Optimize the events query that's running slowly"
"Add a many-to-many relationship between events and tags"
"Create migrations for a new sponsors feature"
```

**For API Development:**
```
"Create v2 API endpoints for organizations"
"Add filtering and pagination to events API"
"Implement API rate limiting"
```

**For Frontend Tasks:**
```
"Update the events calendar to be mobile-responsive"
"Add a search filter to the organizations page"
"Improve the form validation on contact page"
```

**For Code Reviews:**
```
"Review my changes to the event import system"
"Check for security issues in the new API endpoints"
"Analyze performance of the calendar feed generation"
```

### Project-Specific Context

**Key Directories:**
- `/app` - Core Laravel application
- `/app-modules/api` - API module with versioned controllers
- `/app-modules/event-importer` - Event import handlers for various platforms
- `/resources/views` - Blade templates
- `/app/Filament` - Admin panel resources

**Important Features:**
- Multi-source event importing (Eventbrite, Meetup, Luma)
- Calendar feed generation (iCal format)
- Organization management with status tracking
- Slack integration for community sign-ups
- Filament admin panel for content management

**Testing:**
- PHPUnit tests in `/tests`
- API tests for each version
- Event importer tests with fixtures

Your specialized AI team is ready to help build and improve HackGreenville.com!