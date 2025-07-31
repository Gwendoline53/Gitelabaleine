<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChambresController extends AbstractController
{
    #[Route('/chambres', name: 'app_chambres')]
    public function index(): Response
    {
        return $this->render('chambres/index.html.twig', [
            'controller_name' => 'ChambresController',
        ]);
    }
}
