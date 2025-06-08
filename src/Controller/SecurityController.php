<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $utils)
    {
        return $this->render('security/login.html.twig', [
            'error'         => $utils->getLastAuthenticationError(),
            'last_username' => $utils->getLastUsername(),
            'headerMode'  => 'gris',
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony gère la déconnexion, pas besoin de code ici
    }
}
