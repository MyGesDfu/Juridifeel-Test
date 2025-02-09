<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class InseeAPI
{
    private HttpClientInterface $client;
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $inseeApiKey;

    public function __construct(HttpClientInterface $client, string $baseUrl, string $clientId, string $clientSecret, string $inseeApiKey)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->inseeApiKey = $inseeApiKey;
    }

    public function getEtablissementBySiren(string $siren): array
    {
        // Appel à l'API INSEE
        $response = $this->client->request('GET', 'https://api.insee.fr/entreprises/sirene/V3.11/siren/' . $siren, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->inseeApiKey,
                'Accept' => 'application/json',
            ]
        ]);

        // Traitement des données reçues
        $data = $response->toArray();
        $uniteLegale = $data['uniteLegale'] ?? [];
        $periode = $uniteLegale['periodesUniteLegale'][0] ?? [];

        // Mapper les données avec validation
        $numeroSiren = $this->formatSiren($uniteLegale['siren']) ?? null;
        $nicSiege = $periode['nicSiegeUniteLegale'] ?? null;

        $etablissement = [
            'nom' => $periode['denominationUniteLegale'] ?? 'Non renseigné',
            'formeSociale' => $periode['categorieJuridiqueUniteLegale'] ?? 'Non renseigné',
            'siegeSocial' => $nicSiege ?? 'Non renseigné',
            'numeroSiren' => $numeroSiren ?? 'Non renseigné',
            'numeroSiret' => $numeroSiren && $nicSiege ? $numeroSiren . ' ' . $nicSiege : 'Non renseigné',
            'numeroRCS' => $numeroSiren && isset($periode['denominationUniteLegale']) ? $this->formatRCS($numeroSiren, $periode['denominationUniteLegale']) : 'Non renseigné',
            'immatriculation' => isset($uniteLegale['dateCreationUniteLegale']) ? $this->formatImmatriculation($uniteLegale['dateCreationUniteLegale']) : 'Non renseigné',
            'clotureExerciceSocial' => 'Non disponible', // Besoin d'une autre source
            'numeroTVA' => 'FR' . ($data['uniteLegale']['siren'] ?? 'Non renseigné'),
            'codeNafApe' => $periode['activitePrincipaleUniteLegale'] ?? 'Non renseigné',
            'activitePrincipale' => $periode['activitePrincipaleUniteLegale'] ?? 'Non renseigné',
            'capitalSocial' => 'Non renseigné', // Information manquante dans l'API INSEE
        ];

        return $etablissement;
    }

    // Méthode pour formater le numéro de SIREN
    private function formatSiren(string $siren): string
    {
        return implode(' ', str_split($siren, 3)); // SIREN formaté en groupes de 3 chiffres
    }

    // Méthode pour formater le numéro RCS
    private function formatRCS(string $siren, string $denomination): string
    {
        return $siren . ' RCS ' . $denomination;
    }

    // Méthode pour formater la date d'immatriculation
    private function formatImmatriculation(string $date): string
    {
        if ($date === 'Non renseigné') {
            return $date;
        }

        $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
        return $dateObj ? $dateObj->format('d F Y') : $date; // Format jour mois année
    }
}
