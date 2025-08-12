<?php

namespace App\EventListener;

use App\Service\StatisticsService;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

class StatisticsListener
{
    private StatisticsService $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->hasSession()) {
            error_log('Pas de session disponible pour cette requête.');
            return;
        }

        $session = $request->getSession();

        if (!$session->isStarted()) {
            $session->start();
            error_log('Session démarrée manuellement.');
        }

        error_log('Session ID : ' . $session->getId());

        $routeName = $request->attributes->get('_route');
        error_log('Route visitée : ' . $routeName);

        $routeToPageMap = [
            'app_home' => 'accueil',
            'app_about' => 'apropos',
            'app_service' => 'services',
            'app_tarifs' => 'tarifs',
            'app_faq' => 'faq',
            'app_avis' => 'avis',
            'app_contact' => 'contact',
            'app_formulaire' => 'formulaire',
            'app_mention' => 'mentions',
            'page_404' => '404',
        ];

        if (isset($routeToPageMap[$routeName])) {
            $pageKey = $routeToPageMap[$routeName];
            $success = $this->statisticsService->recordVisit($pageKey);
            error_log('Record visit ' . $pageKey . ' success: ' . ($success ? 'oui' : 'non'));
        } else {
            error_log('Route non trackée : ' . $routeName);
        }
    }
}
