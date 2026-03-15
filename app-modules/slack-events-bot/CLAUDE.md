# Slack Events Bot Module

Posts weekly HackGreenville event summaries to configured Slack channels, with automatic updates when events change.

## Commands
- `php artisan slack:delete-old-messages [--days=N]` — delete messages older than N days (default: 90, runs daily at 00:00)
- The `CheckEventsApi` job runs hourly and handles all posting/updating logic

## Testing
- Run module tests: `php artisan test app-modules/slack-events-bot/tests`
- 50+ feature tests covering OAuth, slash commands, rate limiting, message lifecycle
- Uses `Http::fake()` for Slack API, `Queue::fake()` for job dispatch
- Factories: `SlackWorkspaceFactory`, `SlackChannelFactory`, `SlackMessageFactory`

## Architecture
- **Direct DB access**: Queries `Event::published()` directly — does not call the API module
- **Message update strategy**: Tracks posted messages in `slack_messages` table. On subsequent runs, updates existing messages via `chat.update` rather than posting new ones. Deletes excess messages if event count decreases.
- **Message chunking**: Events split across multiple Slack messages when exceeding 3000 char limit. `MessageBuilderService::chunkMessages()` tracks text length for splitting.
- **Slack Block Kit**: Messages use block format (header + section with fields), not plain text. Plain text fallback is included for clients without block support.
- **Queue**: Jobs dispatched to the `slack` queue with retry logic (3 tries, backoff [30s, 120s, 300s])

## Gotchas
- **Message spillover safety** (`UnsafeMessageSpilloverException`): If a week's message count increases but a newer week already has messages posted, the bot refuses to post. This prevents breaking chronological order in Slack channels. Resolution: wait for old messages to age past retention, or manually clean up.
- **Access token encryption**: `SlackWorkspace->access_token` is encrypted with `Crypt::encryptString()` via model accessors. Requires valid `APP_KEY`.
- **Request signature validation**: All webhook requests validated via `ValidateSlackRequest` middleware (HMAC-SHA256, 5-minute timestamp window). Prevents replay attacks.
- **Dev command normalization**: `/dev_add_channel` and `/dev_remove_channel` are auto-normalized to `/add_channel` and `/remove_channel` in the controller.
- **Next week posting**: Next week's events are posted 5 days early (starting Tuesday), not at the start of that week.
- **Error isolation**: A failed Slack API call for one channel doesn't block other channels — errors are logged and the job continues.
- **Orphaned channel cleanup**: If a workspace is missing, its channels are auto-deleted.

## Environment Variables
- `SLACK_SIGNING_SECRET` — HMAC request validation
- `SLACK_CLIENT_ID` / `SLACK_CLIENT_SECRET` — OAuth installation flow
- Bot tokens are stored encrypted per-workspace in the database, not in env

## Slash Commands
Admin-only commands (checks Slack user admin status via `users.info` API):
- `/add_channel` — subscribe current channel to event updates
- `/remove_channel` — unsubscribe current channel
- `/check_api` — trigger immediate event check (15-minute cooldown per workspace)
