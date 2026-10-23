<?php

namespace HackGreenville\SlackEventsBot\Tests\Models;

use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\DatabaseTestCase;

class SlackWorkspaceTest extends DatabaseTestCase
{
    #[Test]
    public function it_encrypts_access_token_on_set()
    {
        $workspace = new SlackWorkspace();
        $plainTextToken = 'fake-token-abcdefghijklmnopqrstuvwxyz';
        $workspace->access_token = $plainTextToken;

        // The attribute should be encrypted, so it should not be the plain text token
        $this->assertNotEquals($plainTextToken, $workspace->getAttributes()['access_token']);
        // And it should be decryptable back to the original
        $this->assertEquals($plainTextToken, Crypt::decryptString($workspace->getAttributes()['access_token']));
    }

    #[Test]
    public function it_decrypts_access_token_on_get()
    {
        $workspace = new SlackWorkspace();
        $plainTextToken = 'fake-token-abcdefghijklmnopqrstuvwxyz';
        $encryptedToken = Crypt::encryptString($plainTextToken);

        // Manually set the encrypted token to simulate it coming from the database
        $workspace->setRawAttributes(['access_token' => $encryptedToken]);

        // When getting the attribute, it should be decrypted
        $this->assertEquals($plainTextToken, $workspace->access_token);
    }

    #[Test]
    public function it_returns_null_for_an_undecryptable_access_token_that_is_not_saved(): void
    {
        $workspace = new SlackWorkspace();
        $workspace->setRawAttributes([
            'access_token' => 'not-a-valid-encrypted-string',
            'team_id' => 'T12345',
        ]);

        $this->assertNull($workspace->access_token);
        $this->assertSame('not-a-valid-encrypted-string', $workspace->getAttributes()['access_token']);
    }

    #[Test]
    public function it_returns_null_for_a_saved_undecryptable_access_token_without_deleting_it(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        DB::table('slack_workspaces')->where('id', $workspace->id)->update([
            'access_token' => 'not-a-valid-encrypted-string',
        ]);

        Log::spy();

        $loaded = SlackWorkspace::query()->findOrFail($workspace->id);

        $this->assertNull($loaded->access_token);
        $this->assertSame(
            'not-a-valid-encrypted-string',
            DB::table('slack_workspaces')->where('id', $workspace->id)->value('access_token')
        );

        Log::shouldHaveReceived('info')->once()->withArgs(function (string $message, array $context) use ($workspace) {
            $payload = $message . json_encode($context);

            return $message === 'Slack workspace access token could not be decrypted.'
                && ($context['team_id'] ?? null) === $workspace->team_id
                && ! array_key_exists('access_token', $context)
                && ! str_contains($payload, 'not-a-valid-encrypted-string');
        });
        Log::shouldNotHaveReceived('warning');
        Log::shouldNotHaveReceived('error');
    }

    #[Test]
    public function it_does_not_log_a_decryption_failure_for_a_valid_null_or_empty_token(): void
    {
        Log::spy();

        $valid = new SlackWorkspace();
        $valid->setRawAttributes([
            'access_token' => Crypt::encryptString('xoxb-ok'),
            'team_id' => 'T_OK',
        ]);
        $this->assertSame('xoxb-ok', $valid->access_token);

        $empty = new SlackWorkspace();
        $empty->setRawAttributes([
            'access_token' => '',
            'team_id' => 'T_EMPTY',
        ]);
        $this->assertNull($empty->access_token);

        $missing = new SlackWorkspace();
        $missing->setRawAttributes([
            'access_token' => null,
            'team_id' => 'T_NULL',
        ]);
        $this->assertNull($missing->access_token);

        Log::shouldNotHaveReceived('info');
        Log::shouldNotHaveReceived('warning');
        Log::shouldNotHaveReceived('error');
    }

    #[Test]
    public function it_does_not_clear_a_token_that_was_replaced_before_the_reset_is_saved(): void
    {
        $workspace = SlackWorkspace::factory()->create();
        $originalToken = $workspace->access_token;
        $stale = SlackWorkspace::query()->findOrFail($workspace->id);

        DB::table('slack_workspaces')->where('id', $workspace->id)->update([
            'access_token' => 'not-a-valid-encrypted-string',
        ]);

        $bad = SlackWorkspace::query()->findOrFail($workspace->id);
        $replacement = Crypt::encryptString('xoxb-replaced');
        DB::table('slack_workspaces')->where('id', $workspace->id)->update([
            'access_token' => $replacement,
        ]);

        $this->assertSame($originalToken, $stale->access_token);
        $this->assertNull($bad->access_token);
        $this->assertSame(
            'xoxb-replaced',
            SlackWorkspace::query()->findOrFail($workspace->id)->access_token
        );
    }
}
