<?php
// src/Security/Voter/ItemVoter.php

// namespace App\Security\Voter;

// use App\Entity\Item;
// use App\Entity\User;
// use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Symfony\Component\Security\Core\Authorization\Voter\Voter;

// final class ItemVoterAttribute
// {
//     const VIEW   = 'ITEM_VIEW';
//     const BORROW = 'ITEM_BORROW';
//     const EDIT   = 'ITEM_EDIT';
//     const DELETE = 'ITEM_DELETE';
//     const MANAGE = 'ITEM_MANAGE';
// }
// final class ItemVoter extends Voter
// {
//     private const ATTRIBUTES = [
//         ItemVoterAttribute::VIEW,
//         ItemVoterAttribute::BORROW,
//         ItemVoterAttribute::EDIT,
//         ItemVoterAttribute::DELETE,
//         ItemVoterAttribute::MANAGE,
//     ];

//     protected function supports(string $attribute, mixed $subject): bool
//     {
//         return in_array($attribute, self::ATTRIBUTES, true)
//             && $subject instanceof Item;
//     }

//     protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
//     {
//         $user = $token->getUser();

//         if (!$user instanceof User) {
//             return false;
//         }

//         /** @var Item $item */
//         $item = $subject;

//         return match ($attribute) {
//             ItemVoterAttribute::VIEW   => $this->canView($item, $user),
//             ItemVoterAttribute::BORROW => $this->canBorrow($item, $user),
//             ItemVoterAttribute::EDIT   => $this->canEdit($item, $user),
//             ItemVoterAttribute::DELETE => $this->canDelete($item, $user),
//             ItemVoterAttribute::MANAGE => $this->canManage($item, $user),
//             default                    => false,
//         };
//     }

//     private function canView(Item $item, User $user): bool
//     {
//         if ($item->getOwner() === $user) {
//             return true;
//         }
//         return $this->sharesCircleWithOwner($item, $user);
//     }

//     private function canBorrow(Item $item, User $user): bool
//     {
//         if ($item->getOwner() === $user) {
//             return false;
//         }

//         //to add if we support availability
//         // if (!$item->isAvailable()) {
//         //     return false;
//         // }

//         return $this->sharesCircleWithOwner($item, $user);
//     }

//     private function canEdit(Item $item, User $user): bool
//     {
//         return $item->getOwner() === $user;
//     }

//     private function canDelete(Item $item, User $user): bool
//     {
//         return $item->getOwner() === $user;
//     }

//     private function canManage(Item $item, User $user): bool
//     {
//         return $item->getOwner() === $user;
//     }

//     private function sharesCircleWithOwner(Item $item, User $user): bool
//     {
//         foreach ($item->getItemCircles() as $itemCircle) {
//             //if one day we manage active/inactive item
//             // if (!$itemCircle->isActive()) {
//             //     continue;
//             // }
//             if ($itemCircle->getCircle()->hasMember($user)) {
//                 return true;
//             }
//         }

//         return false;
//     }
// }



namespace App\Security\Voter;

use App\Entity\Item;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ItemVoter extends Voter
{
    // these values are arbitrary strings; you can use anything
    const BORROW = 'borrow';
    const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the voter doesn't support this attribute, return false
        if (!in_array($attribute, [self::BORROW, self::EDIT])) {
            return false;
        }

        // only vote on `Item` objects
        if (!$subject instanceof Item) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            $vote?->addReason('The user is not logged in.');
            return false;
        }

        // you know $subject is a Item object, thanks to `supports()`
        /** @var Item $item */
        $item = $subject;

        return match($attribute) {
            self::BORROW => $this->canBorrow($item, $user),
            // self::EDIT => $this->canEdit($item, $user, $vote),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canBorrow(Item $item, User $user): bool
    {
        if ($item->getOwner() !== $user) {
            return $this->sharesCircleWithOwner($item, $user);
        }
        return false;
    }

    private function canEdit(Item $item, User $user, ?Vote $vote): bool
    {
        return $item->getOwner() === $user;
    }
    private function sharesCircleWithOwner(Item $item, User $user): bool
    {
        foreach ($item->getItemCircles() as $itemCircle) {
            //if one day we manage active/inactive item
            // if (!$itemCircle->isActive()) {
            //     continue;
            // }
            if ($itemCircle->getCircle()->hasMember($user)) {
                return true;
            }
        }

        return false;
    }
}