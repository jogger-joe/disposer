<?php

namespace App\Controller;

use App\Entity\Supporter;
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
     * @Route("/accepted", name="app_supporter_accepted", methods={"GET"})
     */
    public function accepted(ManagerRegistry $doctrine): Response
    {
        $activeSupporter = $doctrine->getRepository(Supporter::class)->findBy(['status' => 1]);
        return $this->render('supporter_list.html.twig', [
            'title' => 'bestätigte Helfer',
            'supporter' => $activeSupporter,
        ]);
    }

    /**
     * @Route("/unaccepted", name="app_supporter_unaccepted", methods={"GET"})
     */
    public function unaccepted(ManagerRegistry $doctrine): Response
    {
        $inactiveSupporter = $doctrine->getRepository(Supporter::class)->findBy(['status' => 0]);
        return $this->render('supporter_list.html.twig', [
            'title' => 'unbestätigte Helfer',
            'supporter' => $inactiveSupporter,
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, methods={"GET", "POST"})
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
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_supporter_accepted');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Helfer bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/activate/{id}", requirements={"id": "\d+"}, methods={"POST"})
     */
    public function activate(ManagerRegistry $doctrine, int $id): Response
    {
        $supporter = $doctrine->getRepository(Supporter::class)->find($id);
        if (!$supporter) {
            throw $this->createNotFoundException(
                'no supporter found for id ' . $id
            );
        }
        $supporter->setStatus(1);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute('app_supporter_accepted');
    }

    /**
     * @Route("/remove/{id}", requirements={"id": "\d+"}, methods={"DELETE", "POST"})
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $supporter = $doctrine->getRepository(Supporter::class)->find($id);
        if (!$supporter) {
            throw $this->createNotFoundException(
                'no supporter found for id ' . $id
            );
        }
        $doctrine->getManager()->remove($supporter);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute('app_supporter_accepted');
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $supporter = new Supporter();
        $form = $this->createForm(SupporterType::class, $supporter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supporter = $form->getData();
            $supporter->setStatus(1);
            $doctrine->getManager()->persist($supporter);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_supporter_accepted');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neuen Helfer erstellen',
            'form' => $form,
        ]);
    }
}
