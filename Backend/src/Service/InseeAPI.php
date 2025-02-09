<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class InseeAPI
{
    private HttpClientInterface $client;
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;

    // On ne garde plus le cache et l'API key comme propriétés
    private string $inseeApiKey;

    public function __construct(HttpClientInterface $client, string $baseUrl, string $clientId, string $clientSecret, string $inseeApiKey)
    {
        $this->inseeApiKey = $inseeApiKey;
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    private function getAccessToken(): string
    {
        return $_ENV['INSEE_API_ACCESS_TOKEN'] ?? '';
    }

    public function getEtablissementBySiren(string $siren): array
    {
        $token = $this->getAccessToken();

        if (empty($token)) {
            throw new \Exception("Token d'accès manquant ou invalide.");
        }

        $response = $this->client->request('GET', "{$this->baseUrl}/siren/{$siren}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        return $response->toArray();
    }
}
