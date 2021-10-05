<?php

namespace App\Security\Voter;

use App\Entity\CheeseListing;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class CheeseListingVoter extends Voter
{

    private Security $security;

    public function __construct(Security $security){
        $this->security = $security;
    }
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT'])
            && $subject instanceof CheeseListing;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var CheeseListing $subject */

        // ... (check conditions and return true to grant permission) ...
        // edit acces cho owner && admin
        switch ($attribute) {
            case 'EDIT':
                if($subject->getOwner() === $user) {
                    return true;
                }

                if($this->security->isGranted('ROLE_ADMIN')){
                    return true;
                }
               return false;
        }


        throw new \Exception(sprintf('Unhandled attribute "%s"', $attribute));
    }
}
