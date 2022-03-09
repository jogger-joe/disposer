<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Entity\Service;
use App\Form\FurnitureType;
use App\Form\ServiceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/edit/{id}")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $service = $doctrine->getRepository(Service::class)->find($id);
        if (!$service) {
            throw $this->createNotFoundException(
                'no service found for id ' . $id
            );
        }
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service = $form->getData();
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_furniture_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Service bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new")
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service = $form->getData();
            $doctrine->getManager()->persist($service);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_furniture_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neue Dienstleistung erstellen',
            'form' => $form,
        ]);
    }
}
