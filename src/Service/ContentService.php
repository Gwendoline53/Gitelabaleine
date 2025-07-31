<?php
// src/Service/ContentService.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Contenu;

class ContentService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Récupère tous les contenus sous forme ['cle' => 'texte', ...]
     */
    public function getAll(): array
    {
        $contenus = $this->em->getRepository(Contenu::class)->findAll();
        $data = [];
        foreach ($contenus as $contenu) {
            $data[$contenu->getCle()] = $contenu->getTexte();
        }
        return $data;
    }

    /**
     * Met à jour (ou crée) un contenu par sa clé
     */
    public function update(string $cle, string $texte): void
    {
        $contenu = $this->em->getRepository(Contenu::class)->findOneBy(['cle' => $cle]);

        if (!$contenu) {
            $contenu = new Contenu();
            $contenu->setCle($cle);
        }

        $contenu->setTexte($texte);

        $this->em->persist($contenu);
        $this->em->flush();
    }
}