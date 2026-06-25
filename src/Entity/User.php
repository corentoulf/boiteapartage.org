<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cette adresse email.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    /**
     * @var Collection<int, Circle>
     */
    #[ORM\OneToMany(targetEntity: Circle::class, mappedBy: 'created_by')]
    private Collection $ownedCircles;

    /**
     * @var Collection<int, UserCircle>
     */
    #[ORM\OneToMany(targetEntity: UserCircle::class, mappedBy: 'user', cascade: ['persist'], orphanRemoval: true)]
    private Collection $userCircles;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'owner', cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last_name = null;

    #[ORM\Column(options: [
        "default" => false
    ])]
    private ?bool $acceptPhoneContact = false;

    #[ORM\Column(options: [
        "default" => true
    ])]
    private ?bool $acceptEmailContact = true;

    /**
     * @var Collection<int, VerificationRequest>
     */
    #[ORM\OneToMany(targetEntity: VerificationRequest::class, mappedBy: 'user',  cascade: ['persist'], orphanRemoval: true)]
    private Collection $verificationRequests;

    /**
     * @var Collection<int, UserFavoriteItem>
     */
    #[ORM\OneToMany(targetEntity: UserFavoriteItem::class, mappedBy: 'user',  cascade: ['persist'], orphanRemoval: true)]
    private Collection $userFavoriteItems;

    /**
     * @var Collection<int, Loan>
     */
    #[ORM\OneToMany(targetEntity: Loan::class, mappedBy: 'lender', orphanRemoval: true)]
    private Collection $lenderLoans;

    /**
     * @var Collection<int, Loan>
     */
    #[ORM\OneToMany(targetEntity: Loan::class, mappedBy: 'borrower', orphanRemoval: true)]
    private Collection $borrowerLoans;

    public function __construct()
    {
        $this->ownedCircles = new ArrayCollection();
        $this->userCircles = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->verificationRequests = new ArrayCollection();
        $this->userFavoriteItems = new ArrayCollection();
        $this->lenderLoans = new ArrayCollection();
        $this->borrowerLoans = new ArrayCollection();
    }

    public function __toString()
    {
        $nickname = $this->email;
        if(null !== $this->first_name || null !== $this->last_name){
            $nickname = $this->first_name . ' ' . $this->last_name . ' ('. $this->email .')';
        }
        return $nickname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Circle>
     */
    public function getOwnedCircles(): Collection
    {
        return $this->ownedCircles;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, UserCircle>
     */
    public function getUserCircles(): Collection
    {
        return $this->userCircles;
    }

    public function addUserCircle(UserCircle $userCircle): static
    {
        if (!$this->userCircles->contains($userCircle)) {
            $this->userCircles->add($userCircle);
            $userCircle->setUser($this);
        }

        return $this;
    }

    public function removeUserCircle(UserCircle $userCircle): static
    {
        // set the owning side to null (unless already changed)
        if ($this->userCircles->removeElement($userCircle) && $userCircle->getUser() === $this) {
            $userCircle->setUser(null);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOwner($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        // set the owning side to null (unless already changed)
        if ($this->items->removeElement($item) && $item->getOwner() === $this) {
            $item->setOwner(null);
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function isAcceptPhoneContact(): ?bool
    {
        return $this->acceptPhoneContact;
    }

    public function setAcceptPhoneContact(bool $acceptPhoneContact): static
    {
        $this->acceptPhoneContact = $acceptPhoneContact;

        return $this;
    }

    public function isAcceptEmailContact(): ?bool
    {
        return $this->acceptEmailContact;
    }

    public function setAcceptEmailContact(bool $acceptEmailContact): static
    {
        $this->acceptEmailContact = $acceptEmailContact;

        return $this;
    }

    /**
     * @return Collection<int, VerificationRequest>
     */
    public function getVerificationRequests(): Collection
    {
        return $this->verificationRequests;
    }

    public function addVerificationRequest(VerificationRequest $verificationRequest): static
    {
        if (!$this->verificationRequests->contains($verificationRequest)) {
            $this->verificationRequests->add($verificationRequest);
            $verificationRequest->setUser($this);
        }

        return $this;
    }

    public function removeVerificationRequest(VerificationRequest $verificationRequest): static
    {
        // set the owning side to null (unless already changed)
        if ($this->verificationRequests->removeElement($verificationRequest) && $verificationRequest->getUser() === $this) {
            $verificationRequest->setUser(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFavoriteItem>
     */
    public function getUserFavoriteItems(): Collection
    {
        return $this->userFavoriteItems;
    }

    public function addUserFavoriteItem(UserFavoriteItem $userFavoriteItem): static
    {
        if (!$this->userFavoriteItems->contains($userFavoriteItem)) {
            $this->userFavoriteItems->add($userFavoriteItem);
            $userFavoriteItem->setUser($this);
        }

        return $this;
    }

    public function removeUserFavoriteItem(UserFavoriteItem $userFavoriteItem): static
    {
        // set the owning side to null (unless already changed)
        if ($this->userFavoriteItems->removeElement($userFavoriteItem) && $userFavoriteItem->getUser() === $this) {
            $userFavoriteItem->setUser(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Loan>
     */
    public function getLenderLoans(): Collection
    {
        return $this->lenderLoans;
    }

    public function addLenderLoan(Loan $lenderLoan): static
    {
        if (!$this->lenderLoans->contains($lenderLoan)) {
            $this->lenderLoans->add($lenderLoan);
            $lenderLoan->setLender($this);
        }

        return $this;
    }

    public function removeLenderLoan(Loan $lenderLoan): static
    {
        // set the owning side to null (unless already changed)
        if ($this->lenderLoans->removeElement($lenderLoan) && $lenderLoan->getLender() === $this) {
            $lenderLoan->setLender(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Loan>
     */
    public function getBorrowerLoans(): Collection
    {
        return $this->borrowerLoans;
    }

    public function addBorrowerLoan(Loan $borrowerLoan): static
    {
        if (!$this->borrowerLoans->contains($borrowerLoan)) {
            $this->borrowerLoans->add($borrowerLoan);
            $borrowerLoan->setBorrower($this);
        }

        return $this;
    }

    public function removeBorrowerLoan(Loan $borrowerLoan): static
    {
        // set the owning side to null (unless already changed)
        if ($this->borrowerLoans->removeElement($borrowerLoan) && $borrowerLoan->getBorrower() === $this) {
            $borrowerLoan->setBorrower(null);
        }

        return $this;
    }
}
