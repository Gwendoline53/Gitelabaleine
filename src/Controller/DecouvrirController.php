<?php

namespace App\Controller;

use App\Repository\DecouvrirRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DecouvrirController extends AbstractController
{
    #[Route('/decouvrir', name: 'app_decouvrir')]
    public function index(
        DecouvrirRepository $decouvrirRepository,
        RequestStack $requestStack
    ): Response {
        // 🌍 Récupère la locale actuelle
        $locale = $requestStack->getCurrentRequest()->getLocale();

        // 📦 Récupère les blocs de contenu traduits
        $blocs = $decouvrirRepository->findBy(['locale' => $locale]);

        // 🔄 Fallback français si vide
        if (!$blocs) {
            $blocs = $decouvrirRepository->findBy(['locale' => 'fr']);
        }

        // 🧩 Crée un tableau associatif clé => contenu
        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getKey()] = $bloc->getContenu();
        }

        // 🎯 Rendu Twig
        return $this->render('decouvrir/index.html.twig', [
            'contenus' => $contenus,
            'locale' => $locale,
            'mode_edition' => false,
        ]);
    }
}