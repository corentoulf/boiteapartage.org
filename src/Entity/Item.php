<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[Vich\Uploadable]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
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
    private ?ItemType $itemType = null;

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

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'items', fileNameProperty: 'imageName', size: 'imageSize', mimeType: "imageMimeType")]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageMimeType = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, Loan>
     */
    #[ORM\OneToMany(targetEntity: Loan::class, mappedBy: 'item')]
    private Collection $loans;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile instanceof \Symfony\Component\HttpFoundation\File\File) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageMimeType(): ?string
    {
        return $this->imageMimeType;
    }

    public function setImageMimeType(?string $imageMimeType): void
    {
        $this->imageMimeType = $imageMimeType;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function __construct()
    {
        $this->itemCircles = new ArrayCollection();
        $this->loans = new ArrayCollection();
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
        // set the owning side to null (unless already changed)
        if ($this->itemCircles->removeElement($itemCircle) && $itemCircle->getItem() === $this) {
            $itemCircle->setItem(null);
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

    /**
     * @return Collection<int, Loan>
     */
    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function addLoan(Loan $loan): static
    {
        if (!$this->loans->contains($loan)) {
            $this->loans->add($loan);
            $loan->setItem($this);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): static
    {
        if ($this->loans->removeElement($loan)) {
            // set the owning side to null (unless already changed)
            if ($loan->getItem() === $this) {
                $loan->setItem(null);
            }
        }

        return $this;
    }
}
