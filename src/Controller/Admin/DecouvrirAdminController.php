<?php

namespace App\Controller\Admin;

use App\Entity\Decouvrir;
use App\Repository\DecouvrirRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DecouvrirAdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/decouvrir', name: 'admin_decouvrir')]
    public function index(
        DecouvrirRepository $decouvrirRepository,
        Request $request,
    ): Response {
        $locale = $request->query->get('locale', 'fr');

        $blocs = $decouvrirRepository->findBy(['locale' => $locale]);
        if (!$blocs) {
            $blocs = $decouvrirRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getKey()] = $bloc->getContenu();
        }

        return $this->render('decouvrir/index.html.twig', [
            'contenus' => $contenus,
            'locale' => $locale,
            'mode_edition' => true,
        ]);
    }

    #[Route('/admin/decouvrir/update', name: 'admin_decouvrir_update', methods: ['POST'])]
    public function update(Request $request, DecouvrirRepository $decouvrirRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['key']) || !isset($data['texte']) || !isset($data['locale'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $key = $data['key'];
        $texte = $data['texte'];
        $locale = $data['locale'];

        $bloc = $decouvrirRepository->findOneBy(['key' => $key, 'locale' => $locale]);
        if (!$bloc) {
            $bloc = new Decouvrir();
            $bloc->setKey($key);
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
