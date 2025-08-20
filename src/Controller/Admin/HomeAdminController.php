<?php
namespace App\Controller\Admin;

use App\Entity\Home;
use App\Repository\HomeRepository;
use App\Repository\TemoignageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
class HomeAdminController extends AbstractController
{
    private string $locale;
#[Route('/admin/home', name: 'admin_home')]
public function index(
    HomeRepository $homeRepository,
    Request $request,
    TemoignageRepository $temoignageRepository,
): Response {
    $locale = $request->query->get('locale', 'fr');
    $blocs = $homeRepository->findBy(['locale' => $locale]);

    if (!$blocs) {
        $blocs = [];
    }

    $contenus = [];
    foreach ($blocs as $bloc) {
        $contenus[$bloc->getKey()] = $bloc->getContenu();
    }

    $temoignages = $temoignageRepository->findBy([
        'isApproved' => true,
        'locale' => $locale,
    ], ['createdAt' => 'DESC']);

    return $this->render('home/index.html.twig', [
        'contenus' => $contenus,
        'locale' => $locale,
        'temoignages' => $temoignages,
        'mode_edition' => true,
    ]);
}


    #[Route('/admin/home/update', name: 'admin_home_update', methods: ['POST'])]
    public function update(Request $request, HomeRepository $homeRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['key']) || !isset($data['texte']) || !isset($data['locale'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $key = $data['key'];
        $texte = $data['texte'];
        $this->locale = $data['locale'];

        $bloc = $homeRepository->findOneBy(['key' => $key, 'locale' => $this->locale]);
        if (!$bloc) {
            $bloc = new Home();
            $bloc->setKey($key);
            $bloc->setLocale($this->locale);
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
