<?php

namespace App\Http\Clients;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class UpstateClient
{
    public function getEvents(): Collection
    {
        return Http::baseUrl(config('app.events_api_domain'))
            ->get('/api/gtc')
            ->throw()
            ->collect();
    }

    public function getOrgs(): Collection
    {
        return Http::baseUrl(config('app.orgs_api_domain'))
            ->withQueryParameters([
                '_format' => 'json'
            ])
            ->asJson()
            ->throw()
            ->get('rest/organizations')
            ->collect();
    }
}
