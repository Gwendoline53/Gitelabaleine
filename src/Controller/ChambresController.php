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

        $locale = $requestStack->getCurrentRequest()->getLocale();

        $blocs = $chambresRepository->findBy(['locale' => $locale]);

        if (!$blocs) {
            $blocs = $chambresRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getKey()] = $bloc->getContenu();
        }

        return $this->render('chambres/index.html.twig', [
            'contenus' => $contenus,
            'mode_edition' => false,
            'locale' => $locale,
        ]);
    }
}