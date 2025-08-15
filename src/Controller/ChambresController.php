<?php

namespace App\Controller;

use App\Repository\ChambresRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ChambresController extends AbstractController
{
    #[Route('/chambres', name: 'app_chambres')]
    public function index(
        ChambresRepository $chambresRepository,
        RequestStack $requestStack
    ): Response {
        // Récupère la locale courante (ex: 'fr' ou 'en')
        $locale = $requestStack->getCurrentRequest()->getLocale();

        // Cherche les blocs de contenu pour cette locale
        $blocs = $chambresRepository->findBy(['locale' => $locale]);

        // Fallback sur 'fr' si rien trouvé
        if (!$blocs) {
            $blocs = $chambresRepository->findBy(['locale' => 'fr']);
        }

        // Transforme en tableau clé => contenu
        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getKey()] = $bloc->getContenu();
        }

        // Rend la page avec les contenus, sans mode édition
        return $this->render('chambres/index.html.twig', [
            'contenus' => $contenus,
            'mode_edition' => false,
            'locale' => $locale,
        ]);
    }
}