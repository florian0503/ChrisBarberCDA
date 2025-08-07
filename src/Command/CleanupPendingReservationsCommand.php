<?php

namespace App\Command;

use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:cleanup-pending-reservations',
    description: 'Nettoie les réservations en attente de plus de 30 minutes'
)]
class CleanupPendingReservationsCommand extends Command
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Supprime les réservations "pending" créées il y a plus de 30 minutes
        $cutoffTime = new \DateTimeImmutable('-30 minutes');
        
        $pendingReservations = $this->reservationRepository->createQueryBuilder('r')
            ->where('r.status = :status')
            ->andWhere('r.createdAt < :cutoff')
            ->setParameter('status', 'pending')
            ->setParameter('cutoff', $cutoffTime)
            ->getQuery()
            ->getResult();

        $count = count($pendingReservations);
        
        foreach ($pendingReservations as $reservation) {
            $this->em->remove($reservation);
        }
        
        if ($count > 0) {
            $this->em->flush();
            $output->writeln("Supprimé {$count} réservation(s) en attente expirée(s).");
        } else {
            $output->writeln("Aucune réservation en attente à nettoyer.");
        }

        return Command::SUCCESS;
    }
}