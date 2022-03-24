<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Entity\Service;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/required-furniture", name="app_api_required_furniture")
     */
    public function requiredFurniture(ManagerRegistry $doctrine): Response
    {
        $requiredFurniture = [];
        $requiredFurnitureByType = [];
        $furniture = $doctrine->getRepository(Furniture::class)->findAll();
        foreach ($furniture as $currentFurniture) {
            if ($currentFurniture instanceof Furniture && $currentFurniture->getMissingAmount() > 0) {
                $furnitureArray = [
                    'title' => $currentFurniture->getTitle(),
                    'type' => $currentFurniture->getTypeLabel(),
                    'missing' => $currentFurniture->getMissingAmount(),
                ];
                $requiredFurniture[] = $furnitureArray;
                // not the best way, maybe separate route and with db
                if (!array_key_exists($currentFurniture->getType(), $requiredFurnitureByType)) {
                    $requiredFurnitureByType[$currentFurniture->getType()] = [
                        'type' => $currentFurniture->getTypeLabel(),
                        'furniture' => []
                    ];
                }
                $requiredFurnitureByType[$currentFurniture->getType()]['furniture'][] = $furnitureArray;
            }
        }
        return $this->json([
            'stamp' => new DateTime(),
            'requiredFurniture' => $requiredFurniture,
            'requiredFurnitureByType' => $requiredFurnitureByType,
        ]);
    }

    /**
     * @Route("/required-service", name="app_api_required_service")
     */
    public function requiredService(ManagerRegistry $doctrine): Response
    {
        $requiredService = [];
        $service = $doctrine->getRepository(Service::class)->findAll();
        foreach ($service as $currentService) {
            if ($currentService instanceof Service && $currentService->getMissingAmount() > 0) {
                $requiredService[] = [
                    'title' => $currentService->getTitle(),
                    'missing' => $currentService->getMissingAmount(),
                ];
            }
        }
        return $this->json([
            'stamp' => new DateTime(),
            'requiredService' => $requiredService,
        ]);
    }
}
