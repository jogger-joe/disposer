<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Entity\Supporter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $activeSupporter = $doctrine->getRepository(Supporter::class)->findBy(['status' => 1]);
        $newSupporter = $doctrine->getRepository(Supporter::class)->findBy(['status' => 0]);
        $requireRegistrationHousings = $doctrine->getRepository(Housing::class)->findBy(['status' => 0]);
        $partialOccupiedHousings = $doctrine->getRepository(Housing::class)->findBy(['status' => 1]);
        $occupiedHousings = $doctrine->getRepository(Housing::class)->findBy(['status' => 2]);
        $readyForOccupationHousings = $doctrine->getRepository(Housing::class)->findBy(['status' => 3]);
        return $this->render('dashboard.html.twig', [
            'activeSupporter' => count($activeSupporter),
            'newSupporter' => count($newSupporter),
            'maintainingHousings' => count($user->getMaintainedHousings()),
            'requireRegistrationHousings' => count($requireRegistrationHousings),
            'partialOccupiedHousings' => count($partialOccupiedHousings),
            'occupiedHousings' => count($occupiedHousings),
            'readyForOccupationHousings' => count($readyForOccupationHousings)
        ]);
    }
}
