<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\RoleResolver;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_user_index")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $doctrine->getRepository(User::class)->findAll();
        return $this->render('user_list.html.twig', [
            'title' => 'Übersicht der registrierten User',
            'user' => $user,
            'roleLabel' => RoleResolver::ROLE_MAP,
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'no user found for id ' . $id
            );
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_user_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Benutzer bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/password/reset", name="app_user_password_reset")
     */
    public function passwordReset(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_dashboard_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Benutzer bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/remove/{id}")
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'no user found for id ' . $id
            );
        }
        $doctrine->getManager()->remove($user);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute('app_user_index');
    }
}
