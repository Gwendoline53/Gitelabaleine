<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChambremerController extends AbstractController
{
    #[Route('/chambremer', name: 'app_chambremer')]
    public function index(): Response
    {
        return $this->render('chambremer/index.html.twig', [
            'controller_name' => 'ChambremerController',
        ]);
    }
}
