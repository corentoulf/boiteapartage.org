<?php

namespace App\Entity;

use App\Repository\CircleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CircleRepository::class)]
class Circle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postcode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\ManyToOne(inversedBy: 'ownedCircles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $created_by = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    /**
     * @var Collection<int, UserCircle>
     */
    #[ORM\OneToMany(targetEntity: UserCircle::class, mappedBy: 'circle', cascade: ['persist'], orphanRemoval: true)]
    private Collection $userCircles;

    #[ORM\Column(length: 255)]
    private ?string $short_id = null;

    /**
     * @var Collection<int, ItemCircle>
     */
    #[ORM\OneToMany(targetEntity: ItemCircle::class, mappedBy: 'circle', cascade: ['persist'], orphanRemoval: true)]
    private Collection $itemCircles;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $circle_type = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $insee_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lng = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $AddressLabel = null;


    public function __construct()
    {
        $this->userCircles = new ArrayCollection();
        $this->itemCircles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): static
    {
        $this->created_by = $created_by;

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
            $userCircle->setCircle($this);
        }

        return $this;
    }

    public function removeUserCircle(UserCircle $userCircle): static
    {
        if ($this->userCircles->removeElement($userCircle)) {
            // set the owning side to null (unless already changed)
            if ($userCircle->getCircle() === $this) {
                $userCircle->setCircle(null);
            }
        }

        return $this;
    }

    public function getShortId(): ?string
    {
        return $this->short_id;
    }

    public function setShortId(string $short_id): static
    {
        $this->short_id = $short_id;

        return $this;
    }

    /**
     * @return Collection<int, ItemCircle>
     */
    public function getItemCircles(): Collection
    {
        return $this->itemCircles;
    }

    public function addItemCircle(ItemCircle $itemCircle): static
    {
        if (!$this->itemCircles->contains($itemCircle)) {
            $this->itemCircles->add($itemCircle);
            $itemCircle->setCircle($this);
        }

        return $this;
    }

    public function removeItemCircle(ItemCircle $itemCircle): static
    {
        if ($this->itemCircles->removeElement($itemCircle)) {
            // set the owning side to null (unless already changed)
            if ($itemCircle->getCircle() === $this) {
                $itemCircle->setCircle(null);
            }
        }

        return $this;
    }

    public function getCircleType(): ?string
    {
        return $this->circle_type;
    }

    public function setCircleType(?string $circle_type): static
    {
        $this->circle_type = $circle_type;

        return $this;
    }

    public function getInseeCode(): ?string
    {
        return $this->insee_code;
    }

    public function setInseeCode(?string $insee_code): static
    {
        $this->insee_code = $insee_code;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(?string $lng): static
    {
        $this->lng = $lng;

        return $this;
    }

    public function getAddressLabel(): ?string
    {
        return $this->AddressLabel;
    }

    public function setAddressLabel(string $AddressLabel): static
    {
        $this->AddressLabel = $AddressLabel;

        return $this;
    }

}
