<?php

namespace App\Controller\Admin;

use App\Entity\About;
use App\Repository\AboutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AboutAdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/about', name: 'admin_about')]
    public function index(
        AboutRepository $aboutRepository,
        Request $request,
    ): Response {
        $locale = $request->query->get('locale', 'fr');

        $blocs = $aboutRepository->findBy(['locale' => $locale]);
        if (!$blocs) {
            $blocs = $aboutRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getcle()] = $bloc->getContenu();
        }

        return $this->render('about/index.html.twig', [ // ✅ chemin du template modifié
            'contenus' => $contenus,
            'locale' => $locale,
            'mode_edition' => true,
        ]);
    }

    #[Route('/admin/about/update', name: 'admin_about_update', methods: ['POST'])]
    public function update(Request $request, AboutRepository $aboutRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['key']) || !isset($data['texte']) || !isset($data['locale'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $key = $data['key'];
        $texte = $data['texte'];
        $locale = $data['locale'];

        $bloc = $aboutRepository->findOneBy(['key' => $key, 'locale' => $locale]);
        if (!$bloc) {
            $bloc = new About();
            $bloc->setcle($key);
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
