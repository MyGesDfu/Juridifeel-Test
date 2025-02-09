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
            // Appel au service pour obtenir les données de l'établissement
            $data = $this->inseeApiService->getEtablissementBySiren($siren);

            // Vérifie si des données ont été trouvées
            if (empty($data)) {
                return $this->json(['error' => 'Aucune donnée trouvée pour ce SIREN'], 404);
            }

            // Retour des données sous forme de JSON
            return $this->json($data);
        } catch (\Exception $e) {
            // Gestion des erreurs et retour d'un message d'erreur personnalisé
            return $this->json(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()], 500);
        }
    }
}
