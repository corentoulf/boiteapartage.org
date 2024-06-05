<?php

namespace App\Entity;

use App\Repository\ItemCircleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemCircleRepository::class)]
class ItemCircle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'itemCircles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\ManyToOne(inversedBy: 'itemCircles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Circle $circle = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCircle(): ?Circle
    {
        return $this->circle;
    }

    public function setCircle(?Circle $circle): static
    {
        $this->circle = $circle;

        return $this;
    }
}
