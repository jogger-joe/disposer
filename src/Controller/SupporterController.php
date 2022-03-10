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
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $activeSupporter = $doctrine->getRepository(Supporter::class)->findBy(['status' => 1]);
        $inactiveSupporter = $doctrine->getRepository(Supporter::class)->findBy(['status' => 0]);
        return $this->render('supporter_list.html.twig', [
            'activeSupporter' => $activeSupporter,
            'inactiveSupporter' => $inactiveSupporter
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
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_supporter_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Helfer bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/activate/{id}")
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
        return $this->redirectToRoute('app_supporter_index');
    }

    /**
     * @Route("/remove/{id}")
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
        return $this->redirectToRoute('app_supporter_index');
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
            $supporter->setStatus(1);
            $doctrine->getManager()->persist($supporter);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_supporter_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neuen Helfer erstellen',
            'form' => $form,
        ]);
    }
}
