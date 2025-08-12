<?php

namespace App\Command;

use Symfony\Component\Mime\Email;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendFeedbackRequestCommand extends Command
{
    private $reservationRepository;
    private $mailer;
    private $entityManager;

    public function __construct(ReservationRepository $reservationRepository, MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->reservationRepository = $reservationRepository;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setName('app:send-feedback-request')
            ->setDescription('Envoie un e-mail de demande d\'avis aux clients après leur réservation.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable();

        // Récupérer les réservations confirmées, terminées, et sans feedback envoyé
        $reservations = $this->reservationRepository->findByFeedbackToSend($now);

        if (count($reservations) === 0) {
            $output->writeln('Aucune réservation à traiter.');
            return Command::SUCCESS;
        }

        foreach ($reservations as $reservation) {
            // Générer le lien unique, par exemple avec un token stocké dans la réservation
            $feedbackUrl = sprintf('https://ton-site.com/feedback/%s', $reservation->getToken());

            $email = (new Email())
                ->from('noreply@ton-site.com')
                ->to($reservation->getEmail())
                ->subject('Merci pour votre réservation - Donnez-nous votre avis')
                ->html(sprintf(
                    'Bonjour %s,<br><br>Merci pour votre réservation du %s au %s.<br>Pourriez-vous prendre un moment pour nous laisser votre avis ?<br><a href="%s">Donner mon avis</a><br><br>Merci !',
                    htmlspecialchars($reservation->getNom()),
                    $reservation->getDateDebut()->format('d/m/Y'),
                    $reservation->getDateFin()->format('d/m/Y'),
                    $feedbackUrl
                ));

            try {
                $this->mailer->send($email);
                $output->writeln('E-mail envoyé à ' . $reservation->getEmail());

                // Marquer le feedback comme envoyé
                $reservation->setIsFeedbackSent(true);
                $this->entityManager->persist($reservation);
            } catch (\Exception $e) {
                $output->writeln('Erreur envoi e-mail à ' . $reservation->getEmail() . ': ' . $e->getMessage());
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}