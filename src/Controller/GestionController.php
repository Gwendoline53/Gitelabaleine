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
        return $this->render('pages/gestion/index.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }

    // === ROUTES D'ÉDITION ===

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/home', name: 'admin_gestion_home')]
    public function editHome(ContentService $contentService): Response
    {
        return $this->render('home/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/about', name: 'admin_gestion_about')]
    public function editAbout(ContentService $contentService): Response
    {
        return $this->render('about/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/service', name: 'admin_gestion_service')]
    public function editService(ContentService $contentService): Response
    {
        return $this->render('service/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/tarifs', name: 'admin_gestion_tarifs')]
    public function editTarifs(ContentService $contentService): Response
    {
        return $this->render('tarifs/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/faq', name: 'admin_gestion_faq')]
    public function editFaq(ContentService $contentService): Response
    {
        return $this->render('faq/index.html.twig', [
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
    #[Route('/admin/gestion/formulaire', name: 'admin_gestion_formulaire')]
    public function editFormulaire(ContentService $contentService): Response
    {
        return $this->render('formulaire/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }
}