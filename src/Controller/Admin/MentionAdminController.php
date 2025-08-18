<?php

namespace App\Controller\Admin;

use App\Entity\Mention;
use App\Repository\MentionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MentionAdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/mention', name: 'admin_mention')]
    public function index(
        MentionRepository $mentionRepository,
        Request $request,
    ): Response {
        $locale = $request->query->get('locale', 'fr');

        $blocs = $mentionRepository->findBy(['locale' => $locale]);
        if (!$blocs) {
            $blocs = $mentionRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getcle()] = $bloc->getContenu();
        }

        return $this->render('mention/index.html.twig', [
            'contenus' => $contenus,
            'locale' => $locale,
            'mode_edition' => true,
        ]);
    }

    #[Route('/admin/mention/update', name: 'admin_mention_update', methods: ['POST'])]
    public function update(Request $request, MentionRepository $mentionRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['key']) || !isset($data['texte']) || !isset($data['locale'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $key = $data['key'];
        $texte = $data['texte'];
        $locale = $data['locale'];

        $bloc = $mentionRepository->findOneBy(['key' => $key, 'locale' => $locale]);
        if (!$bloc) {
            $bloc = new Mention();
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
