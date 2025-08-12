<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ContactAdminController extends AbstractController
{
    #[Route('/admin/contact', name: 'admin_contact')]
    public function index(ContactRepository $contactRepository, Request $request): Response
    {
        $locale = $request->query->get('locale', 'fr');

        $contact = $contactRepository->findOneBy(['locale' => $locale]);

        if (!$contact) {
            $contact = new Contact();
            $contact->setLocale($locale);
            $contact->setEmail('');
            $contact->setTelephone('');
            $contact->setAdresse('');
            $contact->setTitre('Contacts'); // Valeur par défaut
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

        if (!isset($data['cle'], $data['texte'], $data['locale'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $cle = $data['cle'];
        $texte = $data['texte'];
        $locale = $data['locale'];

        $contact = $contactRepository->findOneBy(['locale' => $locale]);

        if (!$contact) {
            $contact = new Contact();
            $contact->setLocale($locale);
            $em->persist($contact);
        }

        switch ($cle) {
            case 'titre':
                $contact->setTitre($texte);
                break;
            case 'email':
                $contact->setEmail($texte);
                break;
            case 'telephone':
                $contact->setTelephone($texte);
                break;
            case 'adresse':
                $contact->setAdresse($texte);
                break;
            default:
                return new JsonResponse(['success' => false, 'message' => 'Clé invalide'], 400);
        }

        try {
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la sauvegarde'], 500);
        }

        return new JsonResponse(['success' => true]);
    }
}