<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\RoleResolver;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
     * @Route("/edit/{id}", requirements={"id": "\d+"})
     */
    public function edit(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher, int $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        if (!$user instanceof User) {
            throw $this->createNotFoundException(
                'no user found for id ' . $id
            );
        }
        if ($user->isSuperAdmin()) {
            throw $this->createAccessDeniedException(
                'super admins cant be edited!'
            );
        }
        $roleHierarchy = $this->getParameter('security.role_hierarchy.roles');
        $availableRoles = RoleResolver::getAvailableRoleChoices($roleHierarchy, $user);
        $form = $this->createForm(UserType::class, $user, ['roleChoices' => $availableRoles]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            if (!empty($password)) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_user_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Benutzer bearbeiten',
            'form' => $form,
        ]);
    }

    /**
     * @Route("/remove/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'no user found for id ' . $id
            );
        }
        if ($user->isSuperAdmin()) {
            throw $this->createAccessDeniedException(
                'super admins cant be deleted!'
            );
        }
        $doctrine->getManager()->remove($user);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute('app_user_index');
    }
}
