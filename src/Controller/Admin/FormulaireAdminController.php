<?php

namespace App\Controller\Admin;

use App\Entity\Formulaire;
use App\Entity\Message;
use App\Form\FormulaireType;
use App\Repository\FormulaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FormulaireAdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/formulaire', name: 'admin_formulaire')]
    public function index(
        FormulaireRepository $formulaireRepository,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $locale = $request->query->get('locale', 'fr');

        $blocs = $formulaireRepository->findBy(['locale' => $locale]);

        // Fallback si aucun contenu trouvé dans cette langue
        if (!$blocs) {
            $blocs = $formulaireRepository->findBy(['locale' => 'fr']);
        }

        $contenus = [];
        foreach ($blocs as $bloc) {
            $contenus[$bloc->getCle()] = $bloc->getContenu();
        }

        // Création d'un formulaire vide pour permettre le rendu
        $message = new Message();
        $form = $this->createForm(FormulaireType::class, $message);

        return $this->render('formulaire/index.html.twig', [
            'form' => $form->createView(),
            'contenus' => $contenus,
            'locale' => $locale,
            'mode_edition' => true,
        ]);
    }

    #[Route('/admin/formulaire/update', name: 'admin_formulaire_update', methods: ['POST'])]
    public function update(
        Request $request,
        FormulaireRepository $formulaireRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['key']) || !isset($data['texte']) || !isset($data['locale'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $key = $data['key'];
        $texte = $data['texte'];
        $locale = $data['locale'];

        $bloc = $formulaireRepository->findOneBy(['cle' => $key, 'locale' => $locale]);

        if (!$bloc) {
            $bloc = new Formulaire();
            $bloc->setCle($key);
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
