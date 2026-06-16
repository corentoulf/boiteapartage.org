<?php

namespace App\Entity;

use App\Repository\VerificationRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VerificationRequestRepository::class)]
class VerificationRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'verificationRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $requested_at = null;

    #[ORM\Column(length: 255)]
    private ?string $destination_email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRequestedAt(): ?\DateTimeInterface
    {
        return $this->requested_at;
    }

    public function setRequestedAt(\DateTimeInterface $requested_at): static
    {
        $this->requested_at = $requested_at;

        return $this;
    }

    public function getDestinationEmail(): ?string
    {
        return $this->destination_email;
    }

    public function setDestinationEmail(string $destination_email): static
    {
        $this->destination_email = $destination_email;

        return $this;
    }
}
