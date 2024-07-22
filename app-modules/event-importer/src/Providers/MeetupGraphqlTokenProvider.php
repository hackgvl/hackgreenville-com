<?php

namespace HackGreenville\EventImporter\Providers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;

class MeetupGraphqlTokenProvider
{
    public function getBearerToken()
    {
        $jwt_key = $this->getJwtKey();
        $response = Http::asForm()->withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://secure.meetup.com/oauth2/access', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt_key,
        ]);

        return $response->json();
    }

    protected function getJwtKey()
    {
        $privateKey = file_get_contents(config('event-importer-handlers.meetup_graphql_private_key_path'));
        $headers = [
            'kid' => config('event-importer-handlers.meetup_graphql_private_key_id'),
        ];
        $payload = [
            'iss' => config('event-importer-handlers.meetup_graphql_client_id'),
            'sub' => config('event-importer-handlers.meetup_graphql_member_id'),
            'aud' => 'api.meetup.com',
            'iat' => time(),
            'exp' => time() + 240,
        ];
        return JWT::encode($payload, $privateKey, 'RS256', null, $headers);
    }
}
