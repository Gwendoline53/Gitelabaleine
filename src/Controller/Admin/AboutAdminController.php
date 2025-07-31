<?php

namespace App\Controller\Admin;

use App\Repository\AboutRepository;
use App\Entity\About;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AboutAdminController extends AbstractController
{
    #[Route('/admin/about', name: 'admin_about')]
    public function index(AboutRepository $aboutRepository, Request $request): Response
    {
        // Récupère la locale courante depuis la query, défaut 'fr'
        $locale = $request->query->get('locale', 'fr');

        $blocs = $aboutRepository->findBy(['locale' => $locale]);
        if (!$blocs) {
            $blocs = $aboutRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getCle()] = $bloc->getContenu();
        }

        return $this->render('about/about.html.twig', [
            'contenus' => $contenus,
            'mode_edition' => true,
            'locale' => $locale,
        ]);
    }

    #[Route('/admin/about/update', name: 'admin_about_update', methods: ['POST'])]
    public function update(Request $request, AboutRepository $aboutRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['cle']) || !isset($data['texte']) || !isset($data['locale'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $cle = $data['cle'];
        $texte = $data['texte'];
        $locale = $data['locale'];

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