<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Entity\Housing;
use App\Entity\Service;
use App\Entity\Supporter;
use App\Form\SupporterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    /**
     * @Route("/", name="app_public_index")
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
            $supporter->setStatus(0);
            $doctrine->getManager()->persist($supporter);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_register_result');
        }
        return $this->renderForm('register_supporter.html.twig', [
            'title' => 'Unterstützung anbieten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/register_result", name="app_register_result")
     */
    public function registerResult(): Response
    {
        return $this->renderForm('register_result.html.twig');
    }
}
