<?php

namespace App\Controller;

use App\Entity\Furniture;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/furniture")
 */
class FurnitureController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $defaultFurnitures = $doctrine->getRepository(Furniture::class)->findBy([]);
        $extraFurnitures = $doctrine->getRepository(Furniture::class)->findBy([]);
        return $this->render('furnitures.html.twig', [
            'defaultFurnitures' => $defaultFurnitures,
            'extraFurnitures' => $extraFurnitures,
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function edit(ManagerRegistry $doctrine, int $id): Response
    {
        $furniture = $doctrine->getRepository(Furniture::class)->find($id);
        if (!$furniture) {
            throw $this->createNotFoundException(
                'no furniture found for id ' . $id
            );
        }
        return $this->render('furniture.html.twig', [
            'furniture' => $furniture
        ]);
    }
}
