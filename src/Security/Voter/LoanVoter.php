<?php

namespace App\Security\Voter;

use App\Entity\Loan;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LoanVoter extends Voter
{
    const BORROW = 'borrow';
    const CANCEL = 'cancel';
    const LEND = 'lend';
    const ACCEPT_OR_REJECT = "accept_or_reject";

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the voter doesn't support this attribute, return false
        if (!in_array($attribute, [self::BORROW, self::CANCEL, self::LEND, self::ACCEPT_OR_REJECT])) {
            return false;
        }

        // only vote on `Loan` objects
        if (!$subject instanceof Loan) {
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
        /** @var Loan $loan */
        $loan = $subject;

        return match($attribute) {
            self::BORROW => $this->canBorrow($loan, $user),
            self::CANCEL => $this->canCancel($loan, $user),
            self::LEND => $this->canLend($loan, $user),
            self::ACCEPT_OR_REJECT => $this->canAcceptOrReject($loan, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canBorrow(Loan $loan, User $user): bool
    {
        return $loan->getBorrower() === $user;
    }

    private function canCancel(Loan $loan, User $user): bool
    {
        return $loan->getBorrower() === $user && ($loan->getStatus() === 'requested' || $loan->getStatus() === 'accepted');
    }
    private function canLend(Loan $loan, User $user): bool
    {
        return $loan->getLender() === $user;
    }

    private function canAcceptOrReject(Loan $loan, User $user): bool
    {
        return $loan->getLender() === $user && $loan->getStatus() === 'requested';
    }
}