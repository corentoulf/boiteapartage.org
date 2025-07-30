<?php

namespace App\Entity;

use App\Repository\UserFavoriteItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserFavoriteItemRepository::class)]
// #[UniqueEntity(
//     fields: ['item', 'user'],
//     message: 'Cet objet est déjà dans vos favoris.',
//     errorPath: 'user',
// )]
#[ORM\UniqueConstraint(name: 'UNIQ_ITEM_PER_USER', fields: ['user_id', 'item_id'])]
#[UniqueEntity(fields: ['user_id', 'item_id'], message: 'L\'objet est déjà dans les favoris')]
class UserFavoriteItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userFavoriteItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item_id = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getItemId(): ?Item
    {
        return $this->item_id;
    }

    public function setItemId(?Item $item_id): static
    {
        $this->item_id = $item_id;

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
