<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TableaudebordController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/tableaudebord', name: 'admin_tableaudebord')]
    public function index(): Response
    {
        return $this->render('tableaudebord/index.html.twig', [
            'controller_name' => 'TableaudebordController',
        ]);
    }
}
