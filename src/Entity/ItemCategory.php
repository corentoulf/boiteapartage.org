<?php

namespace App\Entity;

use App\Repository\ItemCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemCategoryRepository::class)]
class ItemCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    /**
     * @var Collection<int, ItemType>
     */
    #[ORM\OneToMany(targetEntity: ItemType::class, mappedBy: 'category')]
    private Collection $itemTypes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;


    public function __construct()
    {
        $this->itemTypes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, ItemType>
     */
    public function getItemTypes(): Collection
    {
        return $this->itemTypes;
    }

    public function addItemType(ItemType $itemType): static
    {
        if (!$this->itemTypes->contains($itemType)) {
            $this->itemTypes->add($itemType);
            $itemType->setCategory($this);
        }

        return $this;
    }

    public function removeItemType(ItemType $itemType): static
    {
        // set the owning side to null (unless already changed)
        if ($this->itemTypes->removeElement($itemType) && $itemType->getCategory() === $this) {
            $itemType->setCategory(null);
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

}
