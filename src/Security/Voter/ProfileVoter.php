<?php

namespace App\Security\Voter;

use App\Entity\Profile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProfileVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['GET'])
            && $subject instanceof Profile;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // On récupère le USER grâce à son token
        $user = $token->getUser();

        // On récupère le rôle du USER 
        $role = $user->getRoles()[0];

        // On récupère le profile qui est demandé
        $profile = $subject->getLibelle();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($user->getRoles()[0] === 'ROLE_SUPER_ADMIN'){
            return true;
        }

        if ($attribute === 'GET') 
        {

            if($role==='ROLE_SUPER_ADMIN')
            {
                return true;

            }elseif ($role === 'ROLE_ADMIN' && $profile === 'CAISSIER' || $profile === 'PARTENAIRE')
            {
                return true;

            }elseif($role==='ROLE_PARTENAIRE' && $profile==='ADMIN_PARTENAIRE' || $profile === 'CAISSIER_PARTENAIRE')
            {
                return true;

            }elseif ($role === 'ROLE_ADMIN_PARTENAIRE' && $profile === 'CAISSIER_PARTENAIRE') 
            {
                return true;
            }

        }

        return false;
    }
}
