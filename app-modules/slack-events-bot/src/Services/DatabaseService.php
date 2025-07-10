<?php

namespace HackGreenville\SlackEventsBot\Services;

use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackCooldown;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DatabaseService
{
    public function createMessage(
        Carbon $week,
        string $message,
        string $messageTimestamp,
        string $slackChannelId,
        int $sequencePosition
    ): SlackMessage {
        $channel = SlackChannel::where('slack_channel_id', $slackChannelId)->firstOrFail();

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
        $channel = SlackChannel::where('slack_channel_id', $slackChannelId)->firstOrFail();

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

    public function getMostRecentMessageForChannel(string $slackChannelId): ?array
    {
        $channel = SlackChannel::where('slack_channel_id', $slackChannelId)->first();

        if ( ! $channel) {
            return null;
        }

        $message = SlackMessage::where('channel_id', $channel->id)
            ->orderBy('week', 'desc')
            ->orderBy('sequence_position', 'desc')
            ->first();

        if ( ! $message) {
            return null;
        }

        return [
            'week' => $message->week->toIso8601String(),
            'message' => $message->message,
            'message_timestamp' => $message->message_timestamp,
        ];
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

    public function removeChannel(string $slackChannelId): int
    {
        return SlackChannel::where('slack_channel_id', $slackChannelId)->delete();
    }

    public function deleteMessagesForWeek(Carbon $week): int
    {
        return SlackMessage::whereDate('week', $week->toDateString())->delete();
    }

    /**
     * Deletes a specific message by its Slack channel ID and timestamp.
     * This is used for cleaning up individual messages no longer needed.
     *
     * @param string $slackChannelId The Slack channel ID.
     * @param string $messageTimestamp The Slack message timestamp (ts).
     * @return int The number of deleted records.
     */
    public function deleteMessage(string $slackChannelId, string $messageTimestamp): int
    {
        $channel = SlackChannel::where('slack_channel_id', $slackChannelId)->first();

        if ( ! $channel) {
            Log::warning("Attempted to delete message in non-existent channel: {$slackChannelId}");
            return 0;
        }

        return SlackMessage::where('channel_id', $channel->id)
            ->where('message_timestamp', $messageTimestamp)
            ->delete();
    }


    public function deleteOldMessages(int $daysBack = 90): void
    {
        $cutoffDate = Carbon::now()->subDays($daysBack);

        // Delete old messages
        SlackMessage::whereDate('week', '<', $cutoffDate->startOfWeek()->toDateString())
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
}
