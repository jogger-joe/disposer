<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\RoleResolver;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/admin/profile")
 */
class ProfileController extends AbstractController
{

    /**
     * @Route("/edit", name="app_profile_edit", methods={"GET", "POST"})
     *
     * @throws Exception
     */
    public function editSelf(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // get user object
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        if (!$user instanceof User) {
            throw new Exception('unexpected exception while accessing user.');
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
            return $this->redirectToRoute('app_dashboard_index');
        }
        return $this->renderForm('edit.html.twig', [
            'title' => 'Account anpassen',
            'form' => $form,
        ]);
    }
}
