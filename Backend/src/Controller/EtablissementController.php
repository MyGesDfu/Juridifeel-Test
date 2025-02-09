<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\InseeAPI;

class EtablissementController extends AbstractController
{
    private InseeAPI $inseeApiService;

    public function __construct(InseeAPI $inseeApiService)
    {
        $this->inseeApiService = $inseeApiService;
    }

    #[Route('/api/etablissement/{siren}', name: 'get_etablissement', methods: ['GET'])]
    public function getEtablissement(string $siren): JsonResponse
    {
        try {
            // Récupération des données de l'établissement
            $data = $this->inseeApiService->getEtablissementBySiren($siren);

            if (empty($data)) {
                return $this->json(['error' => 'Aucune donnée trouvée pour ce SIREN'], 404);
            }

            return $this->json($data);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()], 500);
        }
    }

    #[Route('/api/naf/{code}', name: 'get_naf', methods: ['GET'])]
    public function getNaf(string $code): JsonResponse
    {
        try {
            // Récupération des données NAF
            $data = $this->inseeApiService->getNafApeByCode($code);

            if (empty($data)) {
                return $this->json(['error' => 'Aucune donnée trouvée pour ce code NAF'], 404);
            }

            return $this->json($data);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()], 500);
        }
    }

    #[Route('/api/siret/{siret}', name: 'get_siret', methods: ['GET'])]
    public function getSiret(string $siret): JsonResponse
    {
        try {
            // Récupération des données de l'établissement
            $data = $this->inseeApiService->getEtablissementBySiret($siret);

            if (empty($data)) {
                return $this->json(['error' => 'Aucune donnée trouvée pour ce SIRET'], 404);
            }

            return $this->json($data);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()], 500);
        }
    }
}
