<?php

namespace App\Controller;

use App\Repository\HomeRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    private string $locale;

    #[Route('/', name: 'app_home')]
    public function index(
        HomeRepository $homeRepository,
        RequestStack $requestStack,
        Security $security,
        LogoutUrlGenerator $logoutUrlGenerator,
        \App\Repository\TemoignageRepository $temoignageRepository
    ): Response {
        if ($security->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($logoutUrlGenerator->getLogoutPath());
        }

        $this->locale = $requestStack->getCurrentRequest()->getLocale(); // ✅ Initialisation

        $blocs = $homeRepository->findBy(['locale' => $this->locale]);

        if (!$blocs) {
            $blocs = $homeRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getKey()] = $bloc->getContenu();
        }

        $temoignages = $temoignageRepository->findBy([
            'isApproved' => true,
            'locale' => $this->locale,
        ], ['createdAt' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'contenus' => $contenus,
            'locale' => $this->locale,
            'temoignages' => $temoignages,
            'mode_edition' => false,
        ]);
    }
}
