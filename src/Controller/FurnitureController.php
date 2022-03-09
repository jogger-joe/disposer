<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Entity\Service;
use App\Form\FurnitureType;
use App\Service\FurnitureTypeResolver;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/furniture")
 */
class FurnitureController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $furnitureGroups = [];
        foreach (FurnitureTypeResolver::FURNITURE_TYPE_MAP as $value => $label) {
            $furnitureGroups[$label] = $doctrine->getRepository(Furniture::class)->findBy(['type' => $value]);
        }
        $service = $doctrine->getRepository(Service::class)->findAll();
        return $this->render('furniture_list.html.twig', [
            'furnitureGroups' => $furnitureGroups,
            'service' => $service,
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $furniture = $doctrine->getRepository(Furniture::class)->find($id);
        if (!$furniture) {
            throw $this->createNotFoundException(
                'no furniture found for id ' . $id
            );
        }
        $form = $this->createForm(FurnitureType::class, $furniture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $furniture = $form->getData();
            $doctrine->getManager()->flush($furniture);
            return $this->redirectToRoute('app_furniture_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Einrichtungsgegenstand bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new")
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $furniture = new Furniture();
        $form = $this->createForm(FurnitureType::class, $furniture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $furniture = $form->getData();
            $doctrine->getManager()->persist($furniture);
            $doctrine->getManager()->flush($furniture);
            return $this->redirectToRoute('app_furniture_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neuen Einrichtungsgegenstand erstellen',
            'form' => $form,
        ]);
    }
}
