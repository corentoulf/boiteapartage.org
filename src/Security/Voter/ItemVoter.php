<?php

namespace App\Security\Voter;

use App\Entity\Item;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ItemVoter extends Voter
{
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