<?php

namespace App\Controller;

use App\Entity\Housing;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/housing")
 */
class HousingController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $housings = $doctrine->getRepository(Housing::class)->findAll();
        return $this->render('housings.html.twig', [
            'housings' => $housings
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function edit(ManagerRegistry $doctrine, int $id): Response
    {
        $housing = $doctrine->getRepository(Housing::class)->find($id);
        if (!$housing) {
            throw $this->createNotFoundException(
                'no housing found for id ' . $id
            );
        }
        return $this->render('housing.html.twig', [
            'housing' => $housing
        ]);
    }
}
