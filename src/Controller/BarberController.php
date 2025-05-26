<?php
// src/Controller/BarberController.php
namespace App\Controller;

use App\Repository\BarberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BarberController extends AbstractController
{
    #[Route('/reservation/step2', name: 'reservation_step2')]
    public function select(BarberRepository $barberRepository): Response
    {
        $barbers = $barberRepository->findAll();
        return $this->render('reservation/select_barber.html.twig', [
            'barbers' => $barbers,
        ]);
    }
}
