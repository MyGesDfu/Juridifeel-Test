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
            $data = $this->inseeApiService->getEtablissementBySiren($siren);
            return $this->json($data);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Établissement introuvable'], 404);
        }
    }
}
