<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lenderLoans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $lender = null;

    #[ORM\ManyToOne(inversedBy: 'borrowerLoans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $borrower = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $requested_start_date = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $requested_end_date = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $request_message = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $response_message = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $accepted_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $rejected_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $lent_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $returned_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $cancelled_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLender(): ?User
    {
        return $this->lender;
    }

    public function setLender(?User $lender): static
    {
        $this->lender = $lender;

        return $this;
    }

    public function getBorrower(): ?User
    {
        return $this->borrower;
    }

    public function setBorrower(?User $borrower): static
    {
        $this->borrower = $borrower;

        return $this;
    }

    public function getRequestedStartDate(): ?\DateTime
    {
        return $this->requested_start_date;
    }

    public function setRequestedStartDate(\DateTime $requested_start_date): static
    {
        $this->requested_start_date = $requested_start_date;

        return $this;
    }

    public function getRequestedEndDate(): ?\DateTime
    {
        return $this->requested_end_date;
    }

    public function setRequestedEndDate(\DateTime $requested_end_date): static
    {
        $this->requested_end_date = $requested_end_date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getRequestMessage(): ?string
    {
        return $this->request_message;
    }

    public function setRequestMessage(?string $request_message): static
    {
        $this->request_message = $request_message;

        return $this;
    }

    public function getResponseMessage(): ?string
    {
        return $this->response_message;
    }

    public function setResponseMessage(?string $response_message): static
    {
        $this->response_message = $response_message;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status, array $context = []): void
    {
        $this->status = $status;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getAcceptedAt(): ?\DateTime
    {
        return $this->accepted_at;
    }

    public function setAcceptedAt(?\DateTime $accepted_at): static
    {
        $this->accepted_at = $accepted_at;

        return $this;
    }

    public function getRejectedAt(): ?\DateTime
    {
        return $this->rejected_at;
    }

    public function setRejectedAt(?\DateTime $rejected_at): static
    {
        $this->rejected_at = $rejected_at;

        return $this;
    }

    public function getLentAt(): ?\DateTime
    {
        return $this->lent_at;
    }

    public function setLentAt(?\DateTime $lent_at): static
    {
        $this->lent_at = $lent_at;

        return $this;
    }

    public function getReturnedAt(): ?\DateTime
    {
        return $this->returned_at;
    }

    public function setReturnedAt(?\DateTime $returned_at): static
    {
        $this->returned_at = $returned_at;

        return $this;
    }

    public function getCancelledAt(): ?\DateTime
    {
        return $this->cancelled_at;
    }

    public function setCancelledAt(?\DateTime $cancelled_at): static
    {
        $this->cancelled_at = $cancelled_at;

        return $this;
    }
}
