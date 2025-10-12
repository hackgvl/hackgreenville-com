# Slack Events Bot Module
A Laravel module that posts HackGreenville events from the database to configured Slack channels.

## Installation
This module is automatically loaded as it's in the `app-modules` directory.

## Configuration
More information regarding the configuration of the Slack bot API can be found in the readme located at `app-modules/slack-events-bot/README.md`

## Running Migrations
The migrations will run automatically with:
```bash
php artisan migrate
```

## Available Commands
```bash
# Manually check for events and update Slack messages
php artisan slack:check-events
# Delete old messages (default: 90 days)
php artisan slack:delete-old-messages
php artisan slack:delete-old-messages --days=60
```

## Scheduled Tasks
The module automatically schedules:
- Event check: Every hour
- Old message cleanup: Daily
Make sure your Laravel scheduler is running:
```bash
php artisan schedule:work
```

## Slack Commands
The bot supports the following slash commands:
- `/add_channel` - Add the current channel to receive event updates (admin only)
- `/remove_channel` - Remove the current channel from receiving updates (admin only)
- `/check_api` - Manually trigger an event check (rate limited to once per 15 minutes per workspace)

## Routes
- `GET /slack/install` - Display Slack installation button
- `GET /slack/auth` - OAuth callback for Slack
- `POST /slack/events` - Webhook endpoint for Slack events and commands

## Features
- Posts weekly event summaries to configured Slack channels
- Automatically updates messages when events change
- Handles message chunking for large event lists
- Rate limiting for manual checks
- Admin-only channel management
- OAuth installation flow
- Automatic cleanup of old messages
- Direct database integration (no API calls needed)

## How It Works
1. The bot queries the Event model directly every hour for new/updated events
2. Events are filtered to show published events from 1 day ago to 14 days ahead
3. Events are grouped by week (Sunday to Saturday)
4. Messages are posted/updated in configured Slack channels
5. If a week has many events, they're split across multiple messages
6. Messages for the current week and next week (5 days early) are maintained

## Configuration Options
The module can be configured in `config/slack-events-bot.php`:
- `days_to_look_back` - How many days in the past to include events (default: 1)
- `days_to_look_ahead` - How many days in the future to include events (default: 14)
- `max_message_character_length` - Maximum characters per Slack message (default: 3000)
- `check_api_cooldown_minutes` - Cooldown period for manual checks (default: 15)
- `old_messages_retention_days` - Days to keep old messages (default: 90)

## Migration from Python
This module is a Laravel port of the original Python slack-events-bot, now refactored to use the Event model directly instead of making API calls. This provides:
- Better performance (no HTTP overhead)
- Real-time data (no API caching delays)
- Tighter integration with the application
- Easier maintenance and debugging
