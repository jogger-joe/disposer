<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Entity\Housing;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $defaultFurniture = $doctrine->getRepository(Furniture::class)->findBy(['default' => true]);
        $housings = $doctrine->getRepository(Housing::class)->findAll();
        $housingsWithMissingDefaultFurniture = new ArrayCollection();

        return $this->render('dashboard.html.twig', [
            'defaultFurniture' => $defaultFurniture,
            'housingsWithMissingDefaultFurniture' => $housingsWithMissingDefaultFurniture,
        ]);
    }
}
