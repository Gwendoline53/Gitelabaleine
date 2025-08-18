<?php

namespace App\Entity;

use App\Entity\Reservation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $subject;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(length: 255)]
    private string $fromEmail;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $receivedAt;

    #[ORM\Column(type: 'boolean')]
    private bool $isRead = false;

    #[ORM\ManyToOne(targetEntity: Reservation::class)]
    private ?Reservation $reservation = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $adminReply = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $category = null; // ✅ Nouveau champ

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chambre = null;

    public function getChambre(): ?string
    {
        return $this->chambre;
    }

    public function setChambre(?string $chambre): self
    {
        $this->chambre = $chambre;
        return $this;
    }
    // --- Getters et Setters ---

    public function getId(): ?int { return $this->id; }

    public function getSubject(): string { return $this->subject; }
    public function setSubject(string $subject): self { $this->subject = $subject; return $this; }

    public function getBody(): string { return $this->body; }
    public function setBody(string $body): self { $this->body = $body; return $this; }

    public function getFromEmail(): string { return $this->fromEmail; }
    public function setFromEmail(string $fromEmail): self { $this->fromEmail = $fromEmail; return $this; }

    public function getPhoneNumber(): ?string { return $this->phoneNumber; }
    public function setPhoneNumber(?string $phoneNumber): self { $this->phoneNumber = $phoneNumber; return $this; }

    public function getReceivedAt(): \DateTimeImmutable { return $this->receivedAt; }
    public function setReceivedAt(\DateTimeImmutable $receivedAt): self { $this->receivedAt = $receivedAt; return $this; }

    public function isRead(): bool { return $this->isRead; }
    public function setIsRead(bool $isRead): self { $this->isRead = $isRead; return $this; }

    public function getReservation(): ?Reservation { return $this->reservation; }
    public function setReservation(?Reservation $reservation): self { $this->reservation = $reservation; return $this; }

    public function getAdminReply(): ?string { return $this->adminReply; }
    public function setAdminReply(?string $adminReply): static { $this->adminReply = $adminReply; return $this; }

    public function getCategory(): ?string { return $this->category; } // ✅ Getter
    public function setCategory(?string $category): self { $this->category = $category; return $this; } // ✅ Setter
}