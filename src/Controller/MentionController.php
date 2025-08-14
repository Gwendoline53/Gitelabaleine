<?php

namespace App\Controller;

use App\Repository\MentionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MentionController extends AbstractController
{
    #[Route('/mention', name: 'app_mention')]
    public function index(MentionRepository $faqRepository,RequestStack $requestStack): Response
    {
         $locale = $requestStack->getCurrentRequest()->getLocale();

        $blocs = $faqRepository->findBy(['locale' => $locale]);

        if (!$blocs) {
            $blocs = $faqRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getCle()] = $bloc->getContenu();
        }
        return $this->render('mention/index.html.twig', [
            'contenus' => $contenus,
            'mode_edition' => false,
            'locale' => $locale,
        ]);
    }
}
