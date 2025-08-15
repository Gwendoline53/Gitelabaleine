<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChambrenatureController extends AbstractController
{
    #[Route('/chambrenature', name: 'app_chambrenature')]
    public function index(): Response
    {
        return $this->render('chambrenature/index.html.twig', [
            'controller_name' => 'ChambrenatureController',
        ]);
    }
}
