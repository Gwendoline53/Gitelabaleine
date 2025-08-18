<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactAdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/contact', name: 'admin_contact')]
    public function index(ContactRepository $contactRepository, Request $request): Response
    {
        $locale = $request->query->get('locale', 'fr');

        $contact = $contactRepository->findOneBy(['locale' => $locale]);

        // Si aucun contact n'existe pour la locale, en créer un vide
        if (!$contact) {
            $contact = new Contact();
            $contact->setLocale($locale);
        }

        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'locale' => $locale,
            'mode_edition' => true,
        ]);
    }

    #[Route('/admin/contact/update', name: 'admin_contact_update', methods: ['POST'])]
    public function update(Request $request, ContactRepository $contactRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['titre', 'adresse', 'telephone', 'email', 'locale'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return new JsonResponse(['success' => false, 'message' => "Champ manquant : $field"], 400);
            }
        }

        $locale = $data['locale'];
        $contact = $contactRepository->findOneBy(['locale' => $locale]);

        if (!$contact) {
            $contact = new Contact();
            $contact->setLocale($locale);
            $em->persist($contact);
        }

        $contact->setTitre($data['titre']);
        $contact->setAdresse($data['adresse']);
        $contact->setTelephone($data['telephone']);
        $contact->setEmail($data['email']);

        try {
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la sauvegarde'], 500);
        }

        return new JsonResponse(['success' => true]);
    }
}
