<?php

namespace App\Security\Voter;

use App\Entity\Affectation;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AffectationVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST', 'GET', 'EDIT', 'DELET'])
            && $subject instanceof Affectation;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // On récupère le USER grâce à son token
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($user->getRoles()[0] === 'ROLE_PARTENAIRE' || $user->getRoles()[0] === 'ROLE_ADMIN_PARTENAIRE'){
            return true;
        }


        return false;
    }
}
