<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DecouvrirController extends AbstractController
{
    #[Route('/decouvrir', name: 'app_decouvrir')]
    public function index(): Response
    {
        return $this->render('decouvrir/index.html.twig', [
            'controller_name' => 'DecouvrirController',
        ]);
    }
}
