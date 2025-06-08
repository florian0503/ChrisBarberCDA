<?php
// src/Controller/WebhookController.php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\BarberRepository;
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
        BarberRepository $barberRepository
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
            $barberId = $metadata->barber_id;
            $dateStr  = $metadata->date;    // ex. "2025-06-05"
            $timeStr  = $metadata->time;    // ex. "09:00"

            // 5) Reconstitution de la date/heure en DateTimeImmutable
            $scheduledAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i', 
                $dateStr . ' ' . $timeStr
            );

            // 6) Récupération de l’entité Barber (ou null si vide)
            $barber = $barberId ? $barberRepository->find($barberId) : null;

            // 7) Création de la réservation
            $reservation = new Reservation();
            $reservation->setBarber($barber);
            $reservation->setScheduledAt($scheduledAt);
            $reservation->setStripeSessionId($session->id);

            $em->persist($reservation);
            $em->flush();
        }

        // 8) Renvoie un 200 simple à Stripe : tout s’est bien passé
        return new Response('Webhook received', 200);
    }
}
