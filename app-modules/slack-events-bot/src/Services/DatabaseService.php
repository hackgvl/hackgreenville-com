<?php

namespace HackGreenville\SlackEventsBot\Services;

use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackCooldown;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class DatabaseService
{
    public function createMessage(
        Carbon $week,
        string $message,
        string $messageTimestamp,
        string $slackChannelId,
        int $sequencePosition
    ): SlackMessage {
        $channel = $this->resolveChannel($slackChannelId);

        return SlackMessage::create([
            'week' => $week->toDateTimeString(),
            'message' => $message,
            'message_timestamp' => $messageTimestamp,
            'channel_id' => $channel->id,
            'sequence_position' => $sequencePosition,
        ]);
    }

    public function updateMessage(
        Carbon $week,
        string $message,
        string $messageTimestamp,
        string $slackChannelId
    ): int {
        $channel = $this->resolveChannel($slackChannelId);

        return SlackMessage::whereDate('week', $week->toDateString())
            ->where('message_timestamp', $messageTimestamp)
            ->where('channel_id', $channel->id)
            ->update(['message' => $message]);
    }

    public function getMessages(Carbon $week): Collection
    {
        return SlackMessage::with('channel.workspace')
            ->whereDate('week', $week->toDateString())
            ->orderBy('channel_id')
            ->orderBy('sequence_position')
            ->get();
    }

    public function getMostRecentMessageForChannel(string $slackChannelId): ?SlackMessage
    {
        $channel = $this->findChannel($slackChannelId);

        if ( ! $channel) {
            return null;
        }

        return SlackMessage::where('channel_id', $channel->id)
            ->orderBy('week', 'desc')
            ->orderBy('sequence_position', 'desc')
            ->first();
    }

    public function getSlackChannels(): Collection
    {
        return SlackChannel::with('workspace')->get();
    }

    public function addChannel(string $slackChannelId, string $teamId): SlackChannel
    {
        $workspace = SlackWorkspace::where('team_id', $teamId)->firstOrFail();

        return SlackChannel::firstOrCreate(
            ['slack_channel_id' => $slackChannelId],
            ['slack_workspace_id' => $workspace->id]
        );
    }

    /**
     * Remove a channel and its associated messages from the bot.
     */
    public function removeChannel(string $slackChannelId): int
    {
        $channel = $this->findChannel($slackChannelId);

        if ( ! $channel) {
            return 0;
        }

        $channel->messages()->delete();

        return $channel->delete() ? 1 : 0;
    }

    public function deleteMessage(string $slackChannelId, string $messageTimestamp): int
    {
        $channel = $this->findChannel($slackChannelId);

        if ( ! $channel) {
            return 0;
        }

        return SlackMessage::where('channel_id', $channel->id)
            ->where('message_timestamp', $messageTimestamp)
            ->delete();
    }

    public function deleteOldMessages(int $daysBack = 90): void
    {
        $cutoffDate = Carbon::now()->subDays($daysBack);

        // Delete old messages (use Sunday as week start to match BotService)
        SlackMessage::whereDate('week', '<', $cutoffDate->copy()->startOfWeek(Carbon::SUNDAY)->toDateString())
            ->delete();

        // Delete old cooldowns
        SlackCooldown::where('expires_at', '<', $cutoffDate)->delete();
    }

    public function createCooldown(string $accessor, string $resource, int $cooldownMinutes): SlackCooldown
    {
        return SlackCooldown::updateOrCreate(
            [
                'accessor' => $accessor,
                'resource' => $resource,
            ],
            [
                'expires_at' => Carbon::now()->addMinutes($cooldownMinutes),
            ]
        );
    }

    public function getCooldownExpiryTime(string $accessor, string $resource): ?Carbon
    {
        $cooldown = SlackCooldown::where('accessor', $accessor)
            ->where('resource', $resource)
            ->first();

        return $cooldown?->expires_at;
    }

    public function createOrUpdateWorkspace(array $data): SlackWorkspace
    {
        return SlackWorkspace::updateOrCreate(
            ['team_id' => $data['team']['id']],
            [
                'team_name' => $data['team']['name'],
                'access_token' => $data['access_token'],
                'bot_user_id' => $data['bot_user_id'],
            ]
        );
    }

    private function findChannel(string $slackChannelId): ?SlackChannel
    {
        return SlackChannel::where('slack_channel_id', $slackChannelId)->first();
    }

    private function resolveChannel(string $slackChannelId): SlackChannel
    {
        return $this->findChannel($slackChannelId)
            ?? throw new ModelNotFoundException("No query results for SlackChannel [{$slackChannelId}].");
    }
}
