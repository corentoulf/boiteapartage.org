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

    #[ORM\Column]
    private ?\DateTime $requested_start_date = null;

    #[ORM\Column]
    private ?\DateTime $requested_end_date = null;

    #[ORM\Column]
    private ?\DateTime $start_date = null;

    #[ORM\Column]
    private ?\DateTime $end_date = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

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

    public function getStartDate(): ?\DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTime $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTime $end_date): static
    {
        $this->end_date = $end_date;

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
}
