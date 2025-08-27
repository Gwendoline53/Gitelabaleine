<?php
namespace App\Controller;

use App\Repository\HomeRepository;
use App\Repository\TemoignageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        HomeRepository $homeRepository,
        TemoignageRepository $temoignageRepository,
        RequestStack $requestStack
    ): Response {
        $locale = $requestStack->getCurrentRequest()->getLocale();

        // Récupère les blocs traduits
        $blocs = $homeRepository->findBy(['locale' => $locale]);

        // Fallback en français si aucune traduction n'est trouvée
        if (!$blocs) {
            $blocs = $homeRepository->findBy(['locale' => 'fr']);
        }

        // Crée un tableau associatif clé => contenu
        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getKey()] = $bloc->getContenu();
        }

        // Récupère les témoignages traduits
        $temoignages = $temoignageRepository->findBy([
            'isApproved' => true,
            'locale' => $locale,
        ], ['createdAt' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'contenus' => $contenus,
            'locale' => $locale,
            'temoignages' => $temoignages,
            'mode_edition' => false,
        ]);
    }
}