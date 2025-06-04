<?php
// src/Controller/ReservationController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BarberRepository; // Ajoutez cette ligne

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'reservation')]
    public function index(): Response
    {
        // 1) On construit le tableau des prestations
        $prestations = [
            [
                'id'       => 1,
                'title'    => 'Coupe et Taille de la Barbe Étudiant',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '35€',
                'duration' => '45 min',
                'details'  => [
                    'Diagnostic et conseils personnalisés',
                    'Coupe ciseaux / Tondeuse – Cheveux long / courts',
                    'Coiffage avec nos produits CHRISSBARBER COSMETICS',
                    'Taille, traçage, application d\'une serviette chaude',
                    'Huile à barbe de notre gamme',
                ],
            ],
            [
                'id'       => 2,
                'title'    => 'Coupe Étudiant',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '25€',
                'duration' => '30 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
            [
                'id'       => 3,
                'title'    => 'Coupe Enfant -10ans',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '20€',
                'duration' => '30 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
            [
                'id'       => 4,
                'title'    => 'Coupe + Barbe Homme + Design',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '30€',
                'duration' => '45 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
            [
                'id'       => 5,
                'title'    => 'Coupe et Taille de Barbe',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '40€',
                'duration' => '45 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
            [
                'id'       => 6,
                'title'    => 'Coupe',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '35€',
                'duration' => '45 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
            [
                'id'       => 7,
                'title'    => 'Taille de Barbe',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '35€',
                'duration' => '45 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
            [
                'id'       => 8,
                'title'    => 'Etudiant Coupe + Design',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '35€',
                'duration' => '45 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
            [
                'id'       => 9,
                'title'    => 'Etudiant Coupe + Barbe + Design',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '35€',
                'duration' => '45 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
            [
                'id'       => 10,
                'title'    => 'Coupe Etudiant + Design',
                'summary'  => 'Prestation coupe homme , et taille de la Barbe - Diagnostic et conseils p...',
                'price'    => '35€',
                'duration' => '45 min',
                'details'  => [
                    'Diagnostic et conseils',
                    'Coupe ciseaux / Tondeuse',
                ],
            ],
        ];

        return $this->render('reservation/reservation.html.twig', [
            'headerMode'  => 'gris',
            'prestations' => $prestations,
        ]);
    }

    // #[Route('/reservation/step2', name: 'reservation_step2')]
    // public function selectBarber(BarberRepository $barberRepository): Response
    // {
    //     $barbers = $barberRepository->findAll();

    //     return $this->render('reservation/select_barber.html.twig', [
    //         'headerMode'  => 'gris',
    //         'barbers' => $barbers,
    //     ]);
    // }

    #[Route('/reservation/confirm', name: 'reservation_confirm', methods: ['POST'])]
    public function confirm(Request $request, BarberRepository $barberRepository): Response
    {
        // Récupère l'ID du barbier sélectionné ou null pour "Sans préférence"
        $barberId = $request->request->get('barber');
        $barber   = $barberId ? $barberRepository->find($barberId) : null;

        return $this->render('reservation/confirm.html.twig', [
            'barber' => $barber,
        ]);
    }
}
