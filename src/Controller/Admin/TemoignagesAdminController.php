<?php

namespace App\Controller\Admin;

use App\Entity\Temoignage;
use App\Repository\TemoignageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/temoignages')]
class TemoignagesAdminController extends AbstractController
{
    #[Route('/', name: 'admin_temoignages')]
    public function index(Request $request, TemoignageRepository $repository): Response
    {
        // Récupération de la locale depuis la query string (ex: ?locale=fr)
        $locale = $request->query->get('locale', 'fr');

        // Récupération des témoignages selon la langue
        $temoignages = $repository->findBy(['locale' => $locale], ['createdAt' => 'DESC']);

        return $this->render('temoignages_admin/index.html.twig', [
            'temoignages' => $temoignages,
            'locale' => $locale,
        ]);
    }

    #[Route('/approve/{id}', name: 'admin_temoignage_approve', methods: ['POST'])]
    public function approve(Temoignage $temoignage, EntityManagerInterface $em): Response
    {
        $temoignage->setIsApproved(true);
        $em->flush();

        $this->addFlash('success', 'Témoignage approuvé avec succès.');

        return $this->redirectToRoute('admin_temoignages');
    }

    #[Route('/disapprove/{id}', name: 'admin_temoignage_disapprove', methods: ['POST'])]
    public function disapprove(Temoignage $temoignage, EntityManagerInterface $em): Response
    {
        $temoignage->setIsApproved(false);
        $em->flush();

        $this->addFlash('warning', 'Témoignage désapprouvé.');

        return $this->redirectToRoute('admin_temoignages');
    }

    #[Route('/delete/{id}', name: 'admin_temoignage_delete', methods: ['POST'])]
    public function delete(Temoignage $temoignage, EntityManagerInterface $em): Response
    {
        $em->remove($temoignage);
        $em->flush();

        $this->addFlash('danger', 'Témoignage supprimé.');

        return $this->redirectToRoute('admin_temoignages');
    }
}