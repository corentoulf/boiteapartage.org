<?php
// src/Security/PostVoter.php
namespace App\Security;

use App\Entity\UserCircle;
use App\Entity\Circle;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserAllowedActionsVoter extends Voter
{
    // these strings are just invented: you can use anything
    const BROWSE = 'browse';
    const BROWSE_ALL = 'browseAll';
    const CAN_REQUEST_VERIFICATION_EMAIL = "requestVerificationEmail";

    protected function supports(string $attribute, mixed $subject): bool
    {
        // case browse one circle
        if ($subject instanceof Circle && $attribute === self::BROWSE){
            return true;
        }
        // case browse all
        if ($attribute === self::BROWSE_ALL){
            return true;
        }
        // case verification email request
        return $attribute === self::CAN_REQUEST_VERIFICATION_EMAIL;
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
            self::CAN_REQUEST_VERIFICATION_EMAIL => $this->canRequestVerificationEmail($user),
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
        foreach ($userCircles as $uc) {
            if($uc->getCircle()->getId() == $circle->getId()){ $userIsInCircle = true; }
        }
        return count($user->getItems())>=5 && //check user has shared 5 objects at least
        $userIsInCircle &&
        $user->isVerified();
    }

    private function canBrowseAll(User $user): bool
    {
        // true if they have verified their email AND have add at least 5 objects they can browse any circle
        // It's not here that we check which circles the user belongs to, It's done in the controller
        return count($user->getItems())>=5 && //check user has shared 5 objects at least
        $user->isVerified();
    }

    private function canRequestVerificationEmail(User $user): bool
    {
        // If last request was more than 8h ago : OK
        //check last request date
        $now = new DateTime('now');
        $userVerificationRequests = $user->getVerificationRequests()->toArray();
        if(empty($userVerificationRequests)) {
            return true;
        }
        $lastVerificationRequestSent =
            max(
                array_map(
                    fn($request): DateTime => $request->getRequestedAt(),
                    $userVerificationRequests
                )
            );
        $interval = $now->format('U') - $lastVerificationRequestSent->format('U');
        //if last request > 8h ago OK
        return $interval > 28800;
    }
}