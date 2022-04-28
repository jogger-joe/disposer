<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Tag;
use App\Form\TagType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tag")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $tags = $doctrine->getRepository(Tag::class)->findAll();
        return $this->render('tag_list.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $tag = $doctrine->getRepository(Tag::class)->find($id);
        if (!$tag) {
            throw $this->createNotFoundException(
                'no tag found for id ' . $id
            );
        }
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_tag_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Tag bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();
            $doctrine->getManager()->persist($tag);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_tag_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Neuen Tag erstellen',
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
                'no tag found for id ' . $id
            );
        }
        $doctrine->getManager()->remove($service);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute('app_tag_index');
    }
}
