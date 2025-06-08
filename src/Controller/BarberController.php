<?php
// src/Controller/BarberController.php
namespace App\Controller;

use App\Repository\BarberRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BarberController extends AbstractController
{
    #[Route('/reservation/step2', name: 'reservation_step2')]
    public function select(
        Request $request,
        BarberRepository $barberRepository,
        ReservationRepository $reservationRepository
    ): Response {
        $barbers = $barberRepository->findAll();

        $selectedBarber = null;
        if ($barberId = $request->query->get('barber')) {
            $selectedBarber = $barberRepository->find($barberId);
        }

        $dates = [];
        for ($i = 0; $i < 6; $i++) {
            $dates[] = (new \DateTimeImmutable())->modify("+{$i} days");
        }

        $times = [];
        foreach (range(9, 18) as $hour) {
            foreach ([0, 30] as $min) {
                if (($hour === 13 && $min > 30) || ($hour === 18 && $min > 30)) {
                    continue;
                }
                $times[] = sprintf('%02d:%02d', $hour, $min);
            }
        }

        $reserved = [];
        if ($selectedBarber) {
            $start = $dates[0]->setTime(0, 0);
            $end = end($dates)->setTime(23, 59);
            $reservations = $reservationRepository->createQueryBuilder('r')
                ->where('r.barber = :barber')
                ->andWhere('r.scheduledAt BETWEEN :start AND :end')
                ->setParameter('barber', $selectedBarber)
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->getQuery()
                ->getResult();

            foreach ($reservations as $res) {
                $dayKey = $res->getScheduledAt()->format('Y-m-d');
                $timeKey = $res->getScheduledAt()->format('H:i');
                $reserved[$dayKey][$timeKey] = true;
            }
        }

        return $this->render('reservation/select_barber.html.twig', [
            'barbers'       => $barbers,
            'selectedBarber'=> $selectedBarber,
            'dates'         => $dates,
            'times'         => $times,
            'reserved'      => $reserved,
            'headerMode'     => 'gris',
        ]);
    }
}