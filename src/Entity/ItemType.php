<?php

namespace App\Entity;

use App\Repository\ItemTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemTypeRepository::class)]
class ItemType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_1_label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_2_label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_3_label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_4_label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $property_5_label = null;

    #[ORM\ManyToOne(inversedBy: 'itemTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?itemCategory $category = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'itemType')]
    private Collection $items;


    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getProperty1Label(): ?string
    {
        return $this->property_1_label;
    }

    public function setProperty1Label(?string $property_1_label): static
    {
        $this->property_1_label = $property_1_label;

        return $this;
    }

    public function getProperty2Label(): ?string
    {
        return $this->property_2_label;
    }

    public function setProperty2Label(?string $property_2_label): static
    {
        $this->property_2_label = $property_2_label;

        return $this;
    }

    public function getProperty3Label(): ?string
    {
        return $this->property_3_label;
    }

    public function setProperty3Label(?string $property_3_label): static
    {
        $this->property_3_label = $property_3_label;

        return $this;
    }

    public function getProperty4Label(): ?string
    {
        return $this->property_4_label;
    }

    public function setProperty4Label(?string $property_4_label): static
    {
        $this->property_4_label = $property_4_label;

        return $this;
    }

    public function getProperty5Label(): ?string
    {
        return $this->property_5_label;
    }

    public function setProperty5Label(?string $property_5_label): static
    {
        $this->property_5_label = $property_5_label;

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
            $item->setItemType($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getItemType() === $this) {
                $item->setItemType(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?itemCategory
    {
        return $this->category;
    }

    public function setCategory(?itemCategory $category): static
    {
        $this->category = $category;

        return $this;
    }




}
