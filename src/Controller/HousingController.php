<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Form\HousingType;
use App\Form\RecordHousingType;
use App\Service\FurnitureTypeResolver;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/housing")
 */
class HousingController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $housings = $doctrine->getRepository(Housing::class)->findAll();
        return $this->render('housing_list.html.twig', [
            'title' => 'Übersicht der Unterkünfte',
            'housing' => $housings,
            'furnitureTypeLabels' => FurnitureTypeResolver::FURNITURE_TYPE_MAP
        ]);
    }

    /**
     * @Route("/assigned", name="app_housing_assigned")
     */
    public function assigned(): Response
    {
        $user = $this->getUser();
        return $this->render('housing_list.html.twig', [
            'title' => 'Übersicht der mir zugeordneten Unterkünfte',
            'housing' => $user->getMaintainedHousings(),
            'furnitureTypeLabels' => FurnitureTypeResolver::FURNITURE_TYPE_MAP
        ]);
    }

    /**
     * @Route("/record/edit/{id}")
     */
    public function recordEdit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $housing = $doctrine->getRepository(Housing::class)->findOneBy([
            'id' =>$id,
            'status' => -1]);
        if (!$housing) {
            throw $this->createNotFoundException(
                'no housing found for id ' . $id
            );
        }
        $form = $this->createForm(RecordHousingType::class, $housing);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_housing_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Einrichtungsgegenstände der Unterkunft pflegen',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $housing = $doctrine->getRepository(Housing::class)->find($id);
        if (!$housing) {
            throw $this->createNotFoundException(
                'no housing found for id ' . $id
            );
        }
        $form = $this->createForm(HousingType::class, $housing);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_housing_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Unterkunft bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new")
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $housing = new Housing();
        $form = $this->createForm(HousingType::class, $housing);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $housing = $form->getData();
            $doctrine->getManager()->persist($housing);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_housing_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neue Unterkunft erstellen',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/remove/{id}")
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $housing = $doctrine->getRepository(Housing::class)->find($id);
        if (!$housing) {
            throw $this->createNotFoundException(
                'no housing found for id ' . $id
            );
        }
        $doctrine->getManager()->remove($housing);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute('app_housing_index');
    }
}
