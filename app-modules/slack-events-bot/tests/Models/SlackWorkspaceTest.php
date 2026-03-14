<?php

namespace HackGreenville\SlackEventsBot\Tests\Models;

use HackGreenville\SlackEventsBot\Models\SlackWorkspace;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SlackWorkspaceTest extends TestCase
{
    #[Test]
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

    #[Test]
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

    #[Test]
    public function it_throws_on_invalid_encrypted_token()
    {
        $invalidToken = 'not-a-valid-encrypted-string';

        $workspace = new SlackWorkspace;
        $workspace->setRawAttributes([
            'access_token' => $invalidToken,
            'team_id' => 'T12345',
        ]);

        $this->expectException(DecryptException::class);

        $workspace->access_token;
    }
}
