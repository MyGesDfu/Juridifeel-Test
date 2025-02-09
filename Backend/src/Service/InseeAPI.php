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

    public function getNafApeByCode(string $code): array
    {
        // Appel à l'API INSEE
        $response = $this->client->request('GET', "{$this->baseUrl}/metadonnees/V1/codes/nafr2/sousClasse/" . $code, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->inseeApiKey,
                'Accept' => 'application/json',
            ]
        ]);

        // Traitement des données reçues
        $data = $response->toArray();
        $nafApe = $data['intitule'] ?? 'Non renseigné';

        return ['code' => $code, 'intitule' => $nafApe];
    }

    public function getEtablissementBySiren(string $siren): array
    {
        // Appel à l'API INSEE
        $response = $this->client->request('GET', "{$this->baseUrl}/entreprises/sirene/V3.11/siren/" . $siren, [
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

        $informationSiren = [
            'nom' => $periode['denominationUniteLegale'] ?? 'Non renseigné',
            'formeSociale' => $periode['categorieJuridiqueUniteLegale'] ?? 'Non renseigné',
            'numeroSiren' => $numeroSiren ?? 'Non renseigné',
            'numeroSiret' => $numeroSiren && $nicSiege ? $numeroSiren . ' ' . $nicSiege : 'Non renseigné',
            'numeroRCS' => $numeroSiren && isset($periode['denominationUniteLegale']) ? $this->formatRCS($numeroSiren, $periode['denominationUniteLegale']) : 'Non renseigné',
            'immatriculation' => isset($uniteLegale['dateCreationUniteLegale']) ? $this->formatImmatriculation($uniteLegale['dateCreationUniteLegale']) : 'Non renseigné',
            'clotureExerciceSocial' => 'Non disponible',
            'numeroTVA' => 'FR' . ($data['uniteLegale']['siren'] ?? 'Non renseigné'),
            'codeNafApe' => $periode['activitePrincipaleUniteLegale'] ?? 'Non renseigné',
            'activitePrincipale' => $periode['activitePrincipaleUniteLegale'] ?? 'Non renseigné',
            'capitalSocial' => 'Non renseigné',
            'nicSiege' => $nicSiege ?? 'Non renseigné',
        ];

        return $informationSiren;
    }

    public function getEtablissementBySiret(string $siret): array
    {
        // Appel à l'API INSEE
        $response = $this->client->request('GET', "{$this->baseUrl}/entreprises/sirene/V3.11/siret/" . $siret, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->inseeApiKey,
                'Accept' => 'application/json',
            ]
        ]);

        // Traitement des données reçues
        $data = $response->toArray();

        $etablissement = $data['etablissement'] ?? [];
        $adresseEtablissement = $etablissement['adresseEtablissement'] ?? [];
        $informationsSiret = [
            'siegeSocial' => $etablissement['etablissementSiege'] ? true : false,
            'numeroVoieEtablissement' => $adresseEtablissement['numeroVoieEtablissement'] ?? 'Non renseigné',
            'typeVoieEtablissement' => $adresseEtablissement['typeVoieEtablissement'] ?? 'Non renseigné',
            'libelleVoieEtablissement' => $adresseEtablissement['libelleVoieEtablissement'] ?? 'Non renseigné',
            'codePostalEtablissement' => $adresseEtablissement['codePostalEtablissement'] ?? 'Non renseigné',
            'libelleCommuneEtablissement' => $adresseEtablissement['libelleCommuneEtablissement'] ?? 'Non renseigné',
            'dateDernierTraitementEtablissement' => $etablissement['dateDernierTraitementEtablissement'] ? $this->formatImmatriculation($etablissement['dateDernierTraitementEtablissement']) : 'Non renseigné',
        ];

        return $informationsSiret;
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
