<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, ItemCircle>
     */
    #[ORM\OneToMany(targetEntity: ItemCircle::class, mappedBy: 'item', cascade: ['persist'], orphanRemoval: true)]
    private Collection $itemCircles;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?itemType $itemType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_4 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_5 = null;

    public function __construct()
    {
        $this->itemCircles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->property_1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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
            $itemCircle->setItem($this);
        }

        return $this;
    }

    public function removeItemCircle(ItemCircle $itemCircle): static
    {
        if ($this->itemCircles->removeElement($itemCircle)) {
            // set the owning side to null (unless already changed)
            if ($itemCircle->getItem() === $this) {
                $itemCircle->setItem(null);
            }
        }

        return $this;
    }

    public function getItemType(): ?itemType
    {
        return $this->itemType;
    }

    public function setItemType(?itemType $itemType): static
    {
        $this->itemType = $itemType;

        return $this;
    }

    public function getProperty1(): ?string
    {
        return $this->property_1;
    }

    public function setProperty1(?string $property_1): static
    {
        $this->property_1 = $property_1;

        return $this;
    }

    public function getProperty2(): ?string
    {
        return $this->property_2;
    }

    public function setProperty2(?string $property_2): static
    {
        $this->property_2 = $property_2;

        return $this;
    }

    public function getProperty3(): ?string
    {
        return $this->property_3;
    }

    public function setProperty3(?string $property_3): static
    {
        $this->property_3 = $property_3;

        return $this;
    }

    public function getProperty4(): ?string
    {
        return $this->property_4;
    }

    public function setProperty4(?string $property_4): static
    {
        $this->property_4 = $property_4;

        return $this;
    }

    public function getProperty5(): ?string
    {
        return $this->property_5;
    }

    public function setProperty5(?string $property_5): static
    {
        $this->property_5 = $property_5;

        return $this;
    }
}
