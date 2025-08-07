<?php
// src/Controller/WebhookController.php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\BarberRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    /**
     * @Route("/stripe/webhook", name="stripe_webhook", methods={"POST"})
     */
    public function handleWebhook(
        Request $request,
        EntityManagerInterface $em,
        ReservationRepository $reservationRepository
    ): Response {
        // 1) Votre endpoint secret configuré dans Stripe Dashboard :
        $endpointSecret = $_ENV['STRIPE_WEBHOOK_SECRET']; 
        $payload    = $request->getContent();
        $sigHeader  = $request->headers->get('stripe-signature');

        // 2) Vérification de la signature
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch(\UnexpectedValueException $e) {
            // Payload invalide
            return new Response('Invalid payload', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Signature invalide
            return new Response('Invalid signature', 400);
        }

        // 3) On s’intéresse à l’événement checkout.session.completed
        if ($event->type === 'checkout.session.completed') {
            /** @var Session $session */
            $session = $event->data->object;

            // 4) Lecture des metadata
            $metadata = $session->metadata;
            $reservationId = $metadata->reservation_id ?? null;
            
            if (!$reservationId) {
                return new Response('Reservation ID not found in metadata', 400);
            }

            // 5) Trouver la réservation existante
            $reservation = $reservationRepository->find($reservationId);
            
            if (!$reservation) {
                return new Response('Reservation not found', 400);
            }

            // 6) Confirmer la réservation
            $reservation->setStatus('confirmed');
            $reservation->setStripeSessionId($session->id);

            $em->flush();
        }

        // 8) Renvoie un 200 simple à Stripe : tout s’est bien passé
        return new Response('Webhook received', 200);
    }
}
