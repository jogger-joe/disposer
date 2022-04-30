<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Housing;
use App\Entity\HousingFile;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\HousingFileType;
use App\Form\HousingNewType;
use App\Form\HousingType;
use App\Form\MaintainHousingType;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/housing")
 */
class HousingController extends AbstractController
{

    /**
     * @Route("/", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $housings = $doctrine->getRepository(Housing::class)->findAll();
        return $this->render('housing_list.html.twig', [
            'title' => 'Übersicht der Unterkünfte',
            'housing' => $housings
        ]);
    }

    /**
     * @Route("/maintained", name="app_housing_maintained", methods={"GET"})
     */
    public function assigned(): Response
    {
        $user = $this->getUser();
        return $this->render('housing_maintainer_list.html.twig', [
            'title' => 'Übersicht der mir zugeordneten Unterkünfte',
            'housing' => $user->getMaintainedHousings()
        ]);
    }

    /**
     * @Route("/require-registration", name="app_housing_require_registration", methods={"GET"})
     */
    public function requireRegistration(ManagerRegistry $doctrine): Response
    {
        $housings = $doctrine->getRepository(Housing::class)->findBy(['status' => 0]);
        return $this->render('housing_maintainer_list.html.twig', [
            'title' => 'Übersicht der zu erfassenden Unterkünfte',
            'housing' => $housings
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $housing = $doctrine->getRepository(Housing::class)->find($id);
        if (!$housing instanceof Housing) {
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
        return $this->renderForm('housing_edit.html.twig', [
            'title' => 'Unterkunft bearbeiten',
            'housing' => $housing,
            'form' => $form
        ]);
    }

    /**
     * @Route("/comment/{id}", name="app_housing_comment", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function addComment(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $housing = $doctrine->getRepository(Housing::class)->find($id);
        if (!$housing instanceof Housing) {
            throw $this->createNotFoundException(
                'no housing found for id ' . $id
            );
        }
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $housing->addComment($comment);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_housing_edit', ['id' => $id]);
        }
        return $this->renderForm('edit.html.twig', [
            'title' => sprintf('Kommentar für Unterkunft `%s` erstellen', $housing->getTitle()),
            'form' => $form
        ]);
    }

    /**
     * @Route("/file/{id}", name="app_housing_file", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function addFile(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader, int $id): Response
    {
        $housing = $doctrine->getRepository(Housing::class)->find($id);
        if (!$housing instanceof Housing) {
            throw $this->createNotFoundException(
                'no housing found for id ' . $id
            );
        }
        $file = new HousingFile();
        $form = $this->createForm(HousingFileType::class, $file);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $formFile = $form->get('file')->getData();
            if ($formFile) {
                $filename = $fileUploader->upload($formFile);
                $file->setFilename($filename);
            }
            $housing->addFile($file);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_housing_edit', ['id' => $id]);
        }
        return $this->renderForm('edit.html.twig', [
            'title' => sprintf('Bild für Unterkunft `%s` hinzufügen', $housing->getTitle()),
            'form' => $form
        ]);
    }

    /**
     * @Route("/supporter/edit/{id}", name="app_housing_supporter_edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function editAssigned(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $housing = $doctrine->getRepository(Housing::class)->findOneBy(['id' => $id]);
        if (!$housing instanceof Housing) {
            throw $this->createNotFoundException(
                'no matching housing found for id ' . $id
            );
        }
        if ($housing->getStatus() != 0 and (!$housing->getMaintainer() instanceof User || $housing->getMaintainer()->getId() != $user->getId())) {
            throw $this->createNotFoundException(
                'wrong housing state for housing with id ' . $id
            );
        }
        $form = $this->createForm(MaintainHousingType::class, $housing);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_housing_maintained');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Unterkunft pflegen',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $housing = new Housing();
        $form = $this->createForm(HousingNewType::class, $housing);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $housing = $form->getData();
            $doctrine->getManager()->persist($housing);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_housing_edit', ['id' => $housing->getId()]);
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neue Unterkunft erstellen',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/remove/{id}", requirements={"id": "\d+"}, methods={"DELETE", "POST"})
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
