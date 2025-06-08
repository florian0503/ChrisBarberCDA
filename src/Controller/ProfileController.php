<?php
// src/Controller/ProfileController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'app_profile')]
    public function show(): Response
    {
        // app.user est l’entité User authentifiée
        return $this->render('profile/show.html.twig', [
            'user' => $this->getUser(),
            'headerMode'  => 'gris',
        ]);
    }
}
