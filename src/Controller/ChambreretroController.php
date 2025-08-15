<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChambreretroController extends AbstractController
{
    #[Route('/chambreretro', name: 'app_chambreretro')]
    public function index(): Response
    {
        return $this->render('chambreretro/index.html.twig', [
            'controller_name' => 'ChambreretroController',
        ]);
    }
}
