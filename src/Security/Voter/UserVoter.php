<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST', 'DELET', 'VIEW', 'EDIT', 'GET'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        $role = $user->getRoles()[0];
        $profile = $subject->getProfile()->getLibelle();

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

                if(($role==='ROLE_ADMIN' || $role==='ROLE_SUPER_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE')){
                    return true;
                }
                break;

            case 'DELET':

                if(($role==='ROLE_ADMIN' || $role==='ROLE_SUPER_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE')){
                    return true;
                }
                break;

            case 'VIEW':

                if($role === 'ROLE_ADMIN' || $role === 'ROLE_SUPER_ADMIN'){
                    return true;
                }
                break;

            case 'EDIT':

                if(($role==='ROLE_ADMIN' || $role==='ROLE_SUPER_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE')){
                    return true;
                }elseif($role==='ROLE_SUPER_ADMIN'){
                    if($profile==='CAISSIER' || $profile==='PARTENAIRE' || $profile==='ADMIN' || $profile==='SUPER_ADMIN'){
                        return true;
                    }
                }
                break;

            case 'GET':

                if(($role==='ROLE_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE')){
                    return true;
                }elseif(($role==='ROLE_SUPER_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE' || $profile==='ADMIN')){
                    return true;
                }
                break;
        }

        return false;
    }
}
