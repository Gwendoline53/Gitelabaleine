<?php

namespace App\Controller;

use App\Service\StatisticsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AdminconnexionController extends AbstractController
{
    #[Route('/connexionadmin', name: 'app_connexionadmin')]
    public function index(StatisticsService $stats, AuthenticationUtils $authenticationUtils): Response
    {
        $stats->recordVisit('app_connexionadmin');

        // récupérer la dernière erreur d’authentification s’il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // récupérer le dernier nom d’utilisateur saisi par l’utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('adminconnexion/index.html.twig', [
            'controller_name' => 'adminconnexionController',
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }
}