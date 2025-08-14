<?php

namespace App\Controller;

use App\Repository\NavbarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class NavbarController extends AbstractController
{
    #[Route('/navbar', name: 'app_navbar')]
    public function index(NavbarRepository $navbarRepository,RequestStack $requestStack): Response
    {
         $locale = $requestStack->getCurrentRequest()->getLocale();
        $blocs = $navbarRepository->findBy(['locale' => $locale]);

        if (!$blocs) {
            $blocs = $navbarRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getCle()] = $bloc->getContenu();
        }

        return $this->render('navbar/index.html.twig', [
            'contenus' => $contenus,
        ]);
    }
}
