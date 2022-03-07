<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Entity\Housing;
use App\Entity\Service;
use App\Entity\Supporter;
use App\Form\HousingType;
use App\Form\SupporterType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $defaultFurniture = $doctrine->getRepository(Furniture::class)->findBy(['type' => 1]);
        $services = $doctrine->getRepository(Service::class)->findAll();
        $housing = $doctrine->getRepository(Housing::class)->findAll();

        return $this->render('public.html.twig', [
            'furnitures' => $defaultFurniture,
            'services' => $services,
            'housingCount' => count($housing)
        ]);
    }

    /**
     * @Route("/register_supporter", name="app_register_supporter")
     */
    public function registerSupporter(Request $request, ManagerRegistry $doctrine): Response
    {
        $supporter = new Supporter();
        $form = $this->createForm(SupporterType::class, $supporter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supporter = $form->getData();
            $doctrine->getManager()->persist($supporter);
            $doctrine->getManager()->flush($supporter);
            return $this->redirectToRoute('app_public_index');
        }
        return $this->renderForm('register_supporter.html.twig', [
            'title' => 'Unterstützung anbieten',
            'form' => $form,
        ]);
    }
}
