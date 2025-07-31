<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\StatisticsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FormulaireController extends AbstractController
{
    #[Route('/formulaire', name: 'app_formulaire')]
    public function index(
        Request $request,
        StatisticsService $stats,
        EntityManagerInterface $entityManager
    ): Response {
        $stats->recordVisit('app_contact');

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setReceivedAt(new \DateTimeImmutable());
            $message->setIsRead(false);

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Votre message a bien été envoyé.');

            return $this->redirectToRoute('app_formulaire');
        }

        return $this->render('formulaire/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}