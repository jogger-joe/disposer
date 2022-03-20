<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, ManagerRegistry $doctrine): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        if ($this->getUser() instanceof User) {
            $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $user->setLastLogin();
            $doctrine->getManager()->flush();
        }
        return $this->render('login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
