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
        $defaultFurniture = $doctrine->getRepository(Furniture::class)->findBy(['type' => 1]);
        $housings = $doctrine->getRepository(Housing::class)->findAll();
        $housingsWithMissingDefaultFurniture = new ArrayCollection();

        foreach ($housings as $housing) {
            /**
             * @var Housing $housing
             */
            foreach ($defaultFurniture as $currentDefaultFurniture) {
                if (!$housing->getFurnitures()->contains($currentDefaultFurniture)) {
                    $housing->addMissingDefaultFurniture($currentDefaultFurniture);
                }
            }
            if ($housing->getMissingDefaultFurnitures()){
                $housingsWithMissingDefaultFurniture->add($housing);
            }
        }
        return $this->render('dashboard.html.twig', [
            'title' =>  'Dashboard',
            'description' =>  'Hier werden alle Wohnungen mit fehlenden Einrichtungsgegenständen angezeigt. Rot Markiert ist die fehlende Standardeinrichtung. Darunter ist die bestehende Einrichtung zu sehen.',
            'housingsWithMissingDefaultFurniture' => $housingsWithMissingDefaultFurniture,
        ]);
    }
}
