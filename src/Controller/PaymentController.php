<?php
// src/Controller/PaymentController.php

namespace App\Controller;

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
    public function createCheckoutSession(Request $request): RedirectResponse
    {
        $barberId = $request->request->get('barberId', null);
        $date     = $request->request->get('date');
        $time     = $request->request->get('time');

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
            ],
            'success_url' => $this->generateUrl('reservation_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'  => $this->generateUrl('reservation_confirm', [], UrlGeneratorInterface::ABSOLUTE_URL),
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
}}