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

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemCategory $category = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, ItemCircle>
     */
    #[ORM\OneToMany(targetEntity: ItemCircle::class, mappedBy: 'item', cascade: ['persist'], orphanRemoval: true)]
    private Collection $itemCircles;

    public function __construct()
    {
        $this->itemCircles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->description . '(' . $this->category . ')';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getCategory(): ?ItemCategory
    {
        return $this->category;
    }

    public function setCategory(?ItemCategory $category): static
    {
        $this->category = $category;

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
}
