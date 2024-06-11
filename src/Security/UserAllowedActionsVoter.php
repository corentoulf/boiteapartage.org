<?php
// src/Security/PostVoter.php
namespace App\Security;

use App\Entity\UserCircle;
use App\Entity\Circle;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserAllowedActionsVoter extends Voter
{
    // these strings are just invented: you can use anything
    const BROWSE = 'browse';
    const BROWSE_ALL = 'browseAll';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // case browse one circle
        if ($subject instanceof Circle && in_array($attribute, [self::BROWSE])){
            return true;
        }
        // case browse all
        if (in_array($attribute, [self::BROWSE_ALL])){
            return true;
        }

        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Circle object, thanks to `supports()`
        /** @var Circle $circle */
        $circle = $subject;

        return match($attribute) {
            self::BROWSE => $this->canBrowse($circle, $user),
            self::BROWSE_ALL => $this->canBrowseAll($user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canBrowse(Circle $circle, User $user): bool
    {
        // true if they have verified their email AND have add at least 5 objects they can browse any circle AND are part of the circle
        //NOTE: this is as long as all objects are shared in all circles !
        //check that the user is in that circle
        $userCircles = $user->getUserCircles();
        $userIsInCircle = false;
        foreach ($userCircles as $key => $uc) {
            if($uc->getCircle()->getId() == $circle->getId()){ $userIsInCircle = true; }
        }
        if (
            count($user->getItems())>=5 && //check user has shared 5 objects at least
            $userIsInCircle === true &&
            $user->isVerified()
        ) {
            return true;
        }

        return false;
    }

    private function canBrowseAll(User $user): bool
    {
        // true if they have verified their email AND have add at least 5 objects they can browse any circle
        // It's not here that we check which circles the user belongs to, It's done in the controller
        if (
            count($user->getItems())>=5 && //check user has shared 5 objects at least
            $user->isVerified()
        ) {
            return true;
        }

        return false;
    }
}