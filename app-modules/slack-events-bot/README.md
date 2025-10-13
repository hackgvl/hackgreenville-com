# Slack Events Bot Module

A Laravel module that posts HackGreenville events from the database to configured Slack channels.

## Installation

This module is automatically loaded as it's in the `app-modules` directory.

## Configuration

Add the following environment variables to your `.env` file:

```env
SLACK_BOT_TOKEN=xoxb-your-bot-token
SLACK_SIGNING_SECRET=your-signing-secret
SLACK_CLIENT_ID=your-client-id
SLACK_CLIENT_SECRET=your-client-secret
```

## Setting up Slack Credentials for Testing

To obtain the necessary Slack credentials for testing (SLACK_BOT_TOKEN, SLACK_SIGNING_SECRET, SLACK_CLIENT_ID, SLACK_CLIENT_SECRET), you'll need to create a Slack app and configure it. Follow these steps:

1. Run `cp app-modules/slack-events-bot/slackbot-manifest.json app-modules/slack-events-bot/slackbot-manifest.dev.json` to create a copy version of the manifest. More information about Slack bot manifests can be found on their documentation [here](https://docs.slack.dev/app-manifests/). Modify the values in `slackbot-manifest.dev.json` to match your public endpoint. If you don't have a public endpoint, you may need to create one with [ngrok](https://ngrok.com/).

2. Go to [api.slack.com/apps](https://api.slack.com/apps) and click "Create New App". Then, upload your development manifest to get a head start.

3. Get the following environment variables and add them to your `.env` file: `SLACK_BOT_TOKEN`, `SLACK_SIGNING_SECRET`, `SLACK_CLIENT_ID`, `SLACK_CLIENT_SECRET`

4. Next, navigate to your public endpoint and add `/slack/install` at the end. You should see a `Add to Slack` button displayed if it worked correctly!

5. Go through the flow to connect the bot to a server and channel accordingly.

6. In your Slack workspace, try doing the `/check_api`, `/add_channel`, and `/remove_channel` commands.

7. The slackbot should work correctly!

## Publishing Configuration

To publish the configuration file:

```bash
php artisan vendor:publish --tag=slack-events-bot-config
```

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
