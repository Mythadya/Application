<?php

// src/Service/JourFerieFetcher.php (your existing API fetcher)
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class JourFerieFetcher
{
    public function __construct(
        private HttpClientInterface $client
    ) {}

    public function fetchZone(string $zone, int $year): array
    {
        $url = "https://calendrier.api.gouv.fr/jours-feries/{$zone}/{$year}.json";
        return $this->client->request('GET', $url)->toArray();
    }
} 