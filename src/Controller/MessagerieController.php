<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\ReplyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagerieController extends AbstractController
{
    #[Route('/admin/messagerie', name: 'admin_messagerie', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $sort = $request->query->get('sort', 'default');

        $repo = $em->getRepository(Message::class);
        $qb = $repo->createQueryBuilder('m');

        switch ($sort) {
            case 'date_asc':
                $qb->orderBy('m.receivedAt', 'ASC');
                break;
            case 'date_desc':
                $qb->orderBy('m.receivedAt', 'DESC');
                break;
            case 'Réservation':
            case 'Questions':
            case 'Autre':
                $qb->where('m.subject = :subject')
                   ->setParameter('subject', $sort)
                   ->orderBy('m.receivedAt', 'DESC');
                break;
            default:
                $qb->orderBy('m.receivedAt', 'DESC'); // tri par défaut
        }

        $messages = $qb->getQuery()->getResult();

        return $this->render('messagerie/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/admin/messagerie/archive/{id}', name: 'admin_messageriearchive', methods: ['POST'])]
    public function archive(Message $message, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('archive' . $message->getId(), $request->request->get('_token'))) {
            $message->setIsRead(true);
            $em->flush();
            $this->addFlash('success', 'Message archivé avec succès.');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        return $this->redirectToRoute('admin_messagerie');
    }

    #[Route('/admin/messagerie/desarchiver/{id}', name: 'admin_messagerie_desarchiver', methods: ['POST'])]
    public function desarchiver(Message $message, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('desarchiver' . $message->getId(), $request->request->get('_token'))) {
            $message->setIsRead(false);
            $em->flush();
            $this->addFlash('success', 'Message désarchivé avec succès.');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        return $this->redirectToRoute('admin_messagerie');
    }

    #[Route('/admin/messagerie/supprimer/{id}', name: 'admin_messagerie_supprimer', methods: ['POST'])]
    public function supprimer(Message $message, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('supprimer' . $message->getId(), $request->request->get('_token'))) {
            $em->remove($message);
            $em->flush();
            $this->addFlash('success', 'Message supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        return $this->redirectToRoute('admin_messagerie');
    }

    #[Route('/admin/messagerie/repondre/{id}', name: 'admin_messagerie_reply', methods: ['GET', 'POST'])]
    public function reply(
        Message $message,
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $form = $this->createForm(ReplyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $replyContent = $data['reply'];

            $email = (new Email())
                ->from('admin@votre-site.com')
                ->to($message->getFromEmail())
                ->subject('Réponse à votre message : ' . $message->getSubject())
                ->text($replyContent);

            $mailer->send($email);

            $message->setAdminReply($replyContent);
            $em->flush();

            $this->addFlash('success', 'Réponse envoyée au client.');
            return $this->redirectToRoute('admin_messagerie');
        }

        return $this->render('messagerie/reply.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }
}