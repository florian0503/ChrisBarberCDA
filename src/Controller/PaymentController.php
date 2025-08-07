<?php
// src/Controller/PaymentController.php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\BarberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;          
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/reservation/create-checkout-session', name: 'reservation_create_checkout', methods: ['POST'])]
    public function createCheckoutSession(
        Request $request,
        EntityManagerInterface $em,
        BarberRepository $barberRepository
    ): RedirectResponse {
        // Vérifier que l'utilisateur est connecté
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour effectuer une réservation.');
            return $this->redirectToRoute('app_login');
        }
        
        $barberId = $request->request->get('barberId', null);
        $date     = $request->request->get('date');
        $time     = $request->request->get('time');
        
        // Vérifier si le créneau est encore disponible
        $scheduledAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        $barber = $barberId ? $barberRepository->find($barberId) : null;
        
        // Vérifier les conflits
        $existingReservation = $em->getRepository(Reservation::class)
            ->findOneBy([
                'barber' => $barber,
                'scheduledAt' => $scheduledAt
            ]);
            
        if ($existingReservation) {
            $this->addFlash('error', 'Ce créneau n\'est plus disponible.');
            return $this->redirectToRoute('reservation_step2', [
                'barber' => $barberId
            ]);
        }
        
        // Créer la réservation en attente immédiatement
        $reservation = new Reservation();
        $reservation->setUser($this->getUser());
        $reservation->setBarber($barber);
        $reservation->setScheduledAt($scheduledAt);
        $reservation->setStatus('pending');
        
        $em->persist($reservation);
        $em->flush();

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => 3500,
                    'product_data' => [
                        'name' => 'Rendez-vous Coiffure',
                        'description' => sprintf(
                            'Barbier ID: %s – %s à %s',
                            $barberId ?? 'Sans préférence',
                            $date,
                            $time
                        ),
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [
                'barber_id' => $barberId ?? '',
                'date'      => $date,
                'time'      => $time,
                'user_id'   => $this->getUser()?->getId() ?? '',
                'reservation_id' => $reservation->getId(),
            ],
            'success_url' => $this->generateUrl('reservation_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'  => $this->generateUrl('reservation_cancel', [
                'id' => $reservation->getId()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // 5) On redirige immédiatement vers l’URL de la session Checkout
        return $this->redirect($session->url, 303);
    }

    #[Route('/reservation/success', name: 'reservation_success')]
    public function success(): Response
    {
        return $this->render('reservation/success.html.twig', [
            'headerMode'  => 'gris',
        ]);
    }

    #[Route('/reservation/cancel/{id}', name: 'reservation_cancel')]
    public function cancel(int $id, EntityManagerInterface $em): Response
    {
        $reservation = $em->getRepository(Reservation::class)->find($id);
        
        if ($reservation && $reservation->getStatus() === 'pending') {
            $em->remove($reservation);
            $em->flush();
            $this->addFlash('info', 'Votre réservation a été annulée.');
        }
        
        return $this->redirectToRoute('reservation_step2');
    }
}