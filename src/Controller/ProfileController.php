<?php
// src/Controller/ProfileController.php
namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'app_profile')]
    public function show(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        $now = new \DateTimeImmutable();
        
        // Récupérer les réservations de l'utilisateur
        $upcomingReservations = $reservationRepository->createQueryBuilder('r')
            ->where('r.user = :user')
            ->andWhere('r.scheduledAt >= :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->orderBy('r.scheduledAt', 'ASC')
            ->getQuery()
            ->getResult();
            
        $pastReservations = $reservationRepository->createQueryBuilder('r')
            ->where('r.user = :user')
            ->andWhere('r.scheduledAt < :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->orderBy('r.scheduledAt', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'upcomingReservations' => $upcomingReservations,
            'pastReservations' => $pastReservations,
            'headerMode' => 'gris',
        ]);
    }
}
