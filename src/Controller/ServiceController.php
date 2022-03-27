<?php

namespace App\Controller;

use App\Entity\Service;
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
     * @Route("/", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $service = $doctrine->getRepository(Service::class)->findAll();
        return $this->render('service_list.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, methods={"GET", "POST"})
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
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_service_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Service bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
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
            return $this->redirectToRoute('app_service_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neue Dienstleistung erstellen',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/remove/{id}", requirements={"id": "\d+"}, methods={"DELETE", "POST"})
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $service = $doctrine->getRepository(Service::class)->find($id);
        if (!$service) {
            throw $this->createNotFoundException(
                'no service found for id ' . $id
            );
        }
        $doctrine->getManager()->remove($service);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute('app_service_index');
    }
}
