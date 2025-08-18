<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        ContactRepository $contactRepository,
        RequestStack $requestStack
    ): Response {
        // 🌍 Récupère la locale actuelle
        $locale = $requestStack->getCurrentRequest()->getLocale();

        // 📦 Récupère les contacts traduits
        $contacts = $contactRepository->findBy(['locale' => $locale]);

        // 🔄 Fallback français si vide
        if (!$contacts) {
            $contacts = $contactRepository->findBy(['locale' => 'fr']);
        }

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
            'locale' => $locale,
            'mode_edition' => false,
        ]);
    }
}
