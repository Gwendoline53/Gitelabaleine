<?php

namespace App\Controller;

use App\Service\StatisticsService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatisticsController extends AbstractController
{
    private StatisticsService $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }
    #[Route('/admin/stats', name: 'admin_stats')]
    public function index(Request $request): Response
    {
        $visitPage = $request->query->get('visit');
        if ($visitPage) {
            $this->statisticsService->recordVisit($visitPage);
            return $this->redirectToRoute('stats');
        }

        $statsData = $this->statisticsService->getStatsWithPercentages();
        $pageNames = $this->statisticsService->getPageNames();

        return $this->render('statistics/index.html.twig', [
            'stats' => $statsData,
            'pageNames' => $pageNames,
            'lastUpdate' => $this->statisticsService->getLastUpdateTime()
        ]);
    }

    #[Route('/api/stats', name: 'stats_api')]
    public function api(): JsonResponse
    {
        $statsData = $this->statisticsService->getStatsWithPercentages();

        return new JsonResponse([
            'stats' => $statsData,
            'lastUpdate' => $this->statisticsService->getLastUpdateTime()
        ]);
    }
}
