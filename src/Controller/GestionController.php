<?php

namespace App\Controller;

use App\Service\StatisticsService;
use App\Service\ContentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class GestionController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion', name: 'admin_gestion')]
    public function index(StatisticsService $stats): Response
    {
        $stats->recordVisit('app_gestion');
        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }

    // === ROUTES D'ÉDITION ===

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/home', name: 'admin_home')]
    public function editHome(ContentService $contentService): Response
    {
        return $this->render('home/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/about', name: 'admin_about')]
    public function editAbout(ContentService $contentService): Response
    {
        return $this->render('about/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/temoiniage', name: 'admin_gestion_temoiniage')]
    public function editTemoiniage(ContentService $contentService): Response
    {
        return $this->render('temoiniage/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/contact', name: 'admin_contact')]
    public function editContact(ContentService $contentService): Response
    {
        return $this->render('contact/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }
}