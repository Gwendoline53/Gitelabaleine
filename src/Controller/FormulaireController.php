<?php

namespace App\Controller;

use App\Entity\Message;
use App\Service\StatisticsService;
use App\Repository\FormulaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\FormulaireType;
use Symfony\Contracts\Translation\LocaleAwareInterface;

final class FormulaireController extends AbstractController
{
    #[Route('/formulaire', name: 'app_formulaire')]
    public function index(
        Request $request,
    StatisticsService $stats,
    EntityManagerInterface $entityManager,
    FormulaireRepository $formulaireRepository
    ): Response {
       $stats->recordVisit('app_contact');

    // Récupération locale
    $locale = $request->getLocale();

    // Contenus dynamiques depuis la base
    $blocs = $formulaireRepository->findBy(['locale' => $locale]);
    $contenus = [];
    foreach ($blocs as $bloc) {
        $contenus[$bloc->getCle()] = $bloc->getContenu();
    }

    $message = new Message();
    $form = $this->createForm(FormulaireType::class, $message);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $message->setReceivedAt(new \DateTimeImmutable());
        $message->setIsRead(false);

        $entityManager->persist($message);
        $entityManager->flush();

        $this->addFlash('success', 'Votre message a bien été envoyé.');

        return $this->redirectToRoute('app_formulaire', ['_locale' => $locale]);
    }

    return $this->render('formulaire/index.html.twig', [
        'form' => $form->createView(),
        'contenus' => $contenus,
        'locale' => $locale,
        'mode_edition' => false,
    ]);
    }
}