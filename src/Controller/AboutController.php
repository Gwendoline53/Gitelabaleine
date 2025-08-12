<?php

namespace App\Controller;

use App\Repository\AboutRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(
        AboutRepository $aboutRepository,
        RequestStack $requestStack
    ): Response {
        // Récupère la locale courante (ex: 'fr' ou 'en')
        $locale = $requestStack->getCurrentRequest()->getLocale();

        // Cherche les blocs de contenu pour cette locale
        $blocs = $aboutRepository->findBy(['locale' => $locale]);

        // Fallback sur 'fr' si rien trouvé
        if (!$blocs) {
            $blocs = $aboutRepository->findBy(['locale' => 'fr']);
        }

        // Transforme en tableau clé => contenu
        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getCle()] = $bloc->getContenu();
        }

        // Rend la page avec les contenus, sans mode édition
        return $this->render('about/index.html.twig', [
            'contenus' => $contenus,
            'mode_edition' => false,
            'locale' => $locale,
        ]);
    }
}