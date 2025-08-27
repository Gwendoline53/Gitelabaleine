<?php

namespace App\Controller\Admin;

use App\Entity\Chambres;
use App\Repository\ChambresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ChambresAdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/chambres', name: 'admin_chambres')]
    public function index(
        ChambresRepository $chambresRepository,
        Request $request,
    ): Response {
        $locale = $request->query->get('locale', 'fr');
        $blocs = $chambresRepository->findBy(['locale' => $locale]);
        
        if (!$blocs) {
            $blocs = $chambresRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getKey()] = $bloc->getContenu();
        }

        return $this->render('chambres/index.html.twig', [
            'contenus' => $contenus,
            'locale' => $locale,
            'mode_edition' => true,
        ]);
    }

    #[Route('/admin/chambres/update', name: 'admin_chambres_update', methods: ['POST'])]
    public function update(Request $request, ChambresRepository $chambresRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['key']) || !isset($data['texte']) || !isset($data['locale'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $key = $data['key'];
        $texte = $data['texte'];
        $locale = $data['locale'];

        $bloc = $chambresRepository->findOneBy(['key' => $key, 'locale' => $locale]);
        if (!$bloc) {
            $bloc = new Chambres();
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