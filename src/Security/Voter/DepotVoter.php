<?php

namespace App\Security\Voter;

use App\Entity\Depot;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DepotVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST', 'GET', 'EDIT', 'DELET'])
            && $subject instanceof Depot;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // On récupère le USER grâce à son token
        $user = $token->getUser();

        // On récupère le rôle du USER qui effectue l'action
        $role = $user->getRoles()[0];

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($user->getRoles()[0] === 'ROLE_SUPER_ADMIN'){
            return true;
        }

        /*
            if($user->getRoles()[0] === 'ROLE_CAISSIER' || $user->getRoles()[0] === 'ROLE_PARTENAIRE'){
                return false;
            }  
        */
        
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {

            case 'POST':

                if($role==='ROLE_ADMIN' || $role==='ROLE_CAISSIER'){
                    return true;
                }
                break;

            case 'GET':

                if($role === 'ROLE_ADMIN'){
                    return true;
                }elseif($role === 'CAISSIER'){
                    if($subject->getUser()->getUsername() === $user->getUsername()){
                        return true;
                    }
                }
                break;

            case 'EDIT':

                if($role === 'ROLE_ADMIN'){
                    return true;
                }elseif($role === 'CAISSIER'){
                    if($subject->getUser()->getUsername() === $user->getUsername()){
                        return true;
                    }
                }
                break;

            case 'DELET':

                if($role === 'ROLE_ADMIN'){
                    return true;
                }elseif($role === 'CAISSIER'){
                    if($subject->getUser()->getUsername() === $user->getUsername()){
                        return true;
                    }
                }
                break;
        }

        return false;
    }
}
