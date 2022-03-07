<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Entity\Supporter;
use App\Form\FurnitureType;
use App\Form\SupporterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/supporter")
 */
class SupporterController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $supporter = $doctrine->getRepository(Supporter::class)->findAll();
        return $this->render('supporter_list.html.twig', [
            'title' => 'Übersicht der Helfer',
            'description' => 'Diese Seite dient nur der Übersicht und Verwaltung von Helfern. Unten sind die vorhandene Helfer aufgelistet.',
            'supporter' => $supporter
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $supporter = $doctrine->getRepository(Supporter::class)->find($id);
        if (!$supporter) {
            throw $this->createNotFoundException(
                'no supporter found for id ' . $id
            );
        }
        $form = $this->createForm(SupporterType::class, $supporter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supporter = $form->getData();
            $doctrine->getManager()->flush($supporter);
            return $this->redirectToRoute('app_supporter_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Helfer bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new")
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $supporter = new Supporter();
        $form = $this->createForm(SupporterType::class, $supporter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supporter = $form->getData();
            $doctrine->getManager()->persist($supporter);
            $doctrine->getManager()->flush($supporter);
            return $this->redirectToRoute('app_supporter_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neuen Helfer erstellen',
            'form' => $form,
        ]);
    }
}
