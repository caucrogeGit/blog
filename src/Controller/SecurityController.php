<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security.login', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        return $this->render('pages/security/login.html.twig',
        [
            'last_username' => $utils->getLastUsername(),
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'security.logout', methods: ['GET'])]
    public function logout(): void
    {
        // Cette méthode est vide, elle ne fait rien car tout est géré par Symfony
    }
}
