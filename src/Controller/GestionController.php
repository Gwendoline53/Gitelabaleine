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
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/gestion/temoiniage', name: 'admin_gestion_temoiniage')]
    public function editTemoiniage(ContentService $contentService): Response
    {
        return $this->render('temoiniage/index.html.twig', [
            'contenus' => $contentService->getAll(),
            'mode_edition' => true,
        ]);
    }
}