<?php

namespace App\Controller;

use App\Repository\TemoignageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TemoignagesController extends AbstractController
{
    #[Route('/admin/temoignages', name: 'admin_temoignages')]
    public function index(TemoignageRepository $temoignageRepository): Response
    {
        // Récupère uniquement les témoignages approuvés
        $temoignages = $temoignageRepository->findBy(
            ['isApproved' => true],
            ['createdAt' => 'DESC']
        );

        return $this->render('temoignages_admin/index.html.twig', [
            'temoignages' => $temoignages,
        ]);
    }
}