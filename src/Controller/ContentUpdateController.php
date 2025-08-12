<?php
// src/Controller/Admin/ContentUpdateController.php

namespace App\Controller;

use App\Entity\About;
use App\Repository\AboutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContentUpdateController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/update-contenu', name: 'admin_update_contenu', methods:['POST'])]
    public function update(Request $request, AboutRepository $aboutRepository, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!isset($data['cle']) || !isset($data['texte']) || !isset($data['locale'])) {
        return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
    }

    $cle = $data['cle'];
    $texte = $data['texte'];
    $locale = $data['locale']; // <-- Récupère ici la locale envoyée par JS

    // Cherche l'entrée existante ou crée une nouvelle
    $bloc = $aboutRepository->findOneBy(['cle' => $cle, 'locale' => $locale]);
    if (!$bloc) {
        $bloc = new About();
        $bloc->setCle($cle);
        $bloc->setLocale($locale);
        $em->persist($bloc);
    }

    $bloc->setContenu($texte);

    try {
        $em->flush();
    } catch (\Exception $e) {
        return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la sauvegarde'], 500);
    }

    return new JsonResponse(['success' => true]);
}
}