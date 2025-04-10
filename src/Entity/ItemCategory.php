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
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'category')]
    private Collection $items;

    /**
     * @var Collection<int, ItemType>
     */
    #[ORM\OneToMany(targetEntity: ItemType::class, mappedBy: 'category')]
    private Collection $itemTypes;


    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->itemTypes = new ArrayCollection();
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
            $item->setCategory($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCategory() === $this) {
                $item->setCategory(null);
            }
        }

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
        if ($this->itemTypes->removeElement($itemType)) {
            // set the owning side to null (unless already changed)
            if ($itemType->getCategory() === $this) {
                $itemType->setCategory(null);
            }
        }

        return $this;
    }

}
