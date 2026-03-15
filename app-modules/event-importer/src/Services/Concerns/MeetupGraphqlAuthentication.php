<?php

namespace HackGreenville\EventImporter\Services\Concerns;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use RuntimeException;

trait MeetupGraphqlAuthentication
{
    private function getBearerToken(): array
    {
        $this->validateConfigValues();

        $jwt_key = $this->getJwtKey();

        $response = Http::asForm()->throw()->post('https://secure.meetup.com/oauth2/access', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt_key,
        ]);

        return $response->json();
    }

    private function getJwtKey(): string
    {
        $privateKey = config('event-import-handlers.meetup_graphql_private_key');

        if (empty($privateKey)) {
            $file_path = config('event-import-handlers.meetup_graphql_private_key_path');

            if ( ! file_exists($file_path)) {
                throw new RuntimeException('File path ' . $file_path . ' does not exist.');
            }

            $privateKey = file_get_contents($file_path);
        }

        $headers = [
            'kid' => config('event-import-handlers.meetup_graphql_private_key_id'),
        ];

        $payload = [
            'iss' => config('event-import-handlers.meetup_graphql_client_id'),
            'sub' => config('event-import-handlers.meetup_graphql_member_id'),
            'aud' => 'api.meetup.com',
            'iat' => time(),
            'exp' => time() + 240,
        ];

        return JWT::encode($payload, $privateKey, 'RS256', null, $headers);
    }

    private function validateConfigValues(): void
    {
        if (config('event-import-handlers.meetup_graphql_client_id') === null) {
            throw new RuntimeException('meetup_graphql_client_id config value must be set.');
        }

        if (config('event-import-handlers.meetup_graphql_member_id') === null) {
            throw new RuntimeException('meetup_graphql_member_id config value must be set.');
        }

        if (config('event-import-handlers.meetup_graphql_private_key_id') === null) {
            throw new RuntimeException('meetup_graphql_private_key_id config value must be set.');
        }

        if (config('event-import-handlers.meetup_graphql_private_key') === null
            && config('event-import-handlers.meetup_graphql_private_key_path') === null) {
            throw new RuntimeException('meetup_graphql_private_key or meetup_graphql_private_key_path config value must be set.');
        }
    }
}
