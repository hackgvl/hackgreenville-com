<?php

namespace HackGreenville\SlackEventsBot\Tests\Models;

use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class SlackWorkspaceTest extends TestCase
{
    /** @test */
    public function it_encrypts_access_token_on_set()
    {
        $workspace = new SlackWorkspace;
        $plainTextToken = 'fake-token-abcdefghijklmnopqrstuvwxyz';
        $workspace->access_token = $plainTextToken;

        // The attribute should be encrypted, so it should not be the plain text token
        $this->assertNotEquals($plainTextToken, $workspace->getAttributes()['access_token']);
        // And it should be decryptable back to the original
        $this->assertEquals($plainTextToken, Crypt::decryptString($workspace->getAttributes()['access_token']));
    }

    /** @test */
    public function it_decrypts_access_token_on_get()
    {
        $workspace = new SlackWorkspace;
        $plainTextToken = 'fake-token-abcdefghijklmnopqrstuvwxyz';
        $encryptedToken = Crypt::encryptString($plainTextToken);

        // Manually set the encrypted token to simulate it coming from the database
        $workspace->setRawAttributes(['access_token' => $encryptedToken]);

        // When getting the attribute, it should be decrypted
        $this->assertEquals($plainTextToken, $workspace->access_token);
    }

    /** @test */
    public function it_handles_decrypt_exception_gracefully()
    {
        $invalidToken = 'not-a-valid-encrypted-string';

        // Manually set an invalid token and team_id to simulate it coming from the database
        $workspace = new SlackWorkspace;
        $workspace->setRawAttributes([
            'access_token' => $invalidToken,
            'team_id' => 'T12345', // Set team_id here
        ]);

        Log::shouldReceive('warning')
            ->once()
            ->with('Could not decrypt access token for workspace. The token might be stored in plain text.', Mockery::subset([
                'workspace_id' => null, // Expect null for a new model
                'team_id' => 'T12345',
            ]));

        // When getting the attribute, it should return the original invalid token
        // and log a warning.
        $this->assertEquals($invalidToken, $workspace->access_token);
    }
}
