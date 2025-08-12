<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class NavbaradminController extends AbstractController
{

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/navbaradmin', name: 'app_navbaradmin')]
    public function index(): Response
    {
        return $this->render('navbaradmin/index.html.twig', [
            'controller_name' => 'NavbaradminController',
        ]);
    }
}
