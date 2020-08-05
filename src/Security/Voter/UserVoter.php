<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Repository\PartenaireRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST', 'DELET', 'EDIT', 'GET'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        $role = $user->getRoles()[0]; // le role du USER qui effectue la création
        $profile = $subject->getProfile()->getLibelle(); // le profile du USER qui est créé

        $part_id = 0; $part_id_connecte = 0;

        if ($user->getPartenaire())
        {
            $part_id_connecte = (int)$user->getPartenaire()->getId();

        }


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
                
                if($role==='ROLE_SUPER_ADMIN' && $profile==='ADMIN'){

                   return true;

                }elseif(($role==='ROLE_SUPER_ADMIN' || $role==='ROLE_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE')){
                    
                    return true;

                }elseif($role==='ROLE_PARTENAIRE' && $profile==='ADMIN_PARTENAIRE'){

                    return true;

                }elseif(($role==='ROLE_PARTENAIRE' || $role==='ROLE_ADMIN_PARTENAIRE') && ($profile==='CAISSIER_PARTENAIRE')){
                    
                    return true;
                }
                break;

            case 'DELET':

                if ($subject->getPartenaire()->getId())
                {
                    $part_id = (int)$subject->getPartenaire()->getId();

                }

                if($role==='ROLE_SUPER_ADMIN' && $profile==='ADMIN')
                {
                    return true;

                }elseif(($role==='ROLE_SUPER_ADMIN' || $role==='ROLE_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE'))
                {
                    return true;

                }elseif(($role==='ROLE_PARTENAIRE' && $profile==='ADMIN_PARTENAIRE') && ($part_id_connecte === $part_id))
                { 
                    return true;
                    
                }elseif(($role==='ROLE_PARTENAIRE' || $role==='ROLE_ADMIN_PARTENAIRE') && 
                    ($profile==='CAISSIER_PARTENAIRE' && $part_id_connecte === $part_id))
                {
                    
                    return true;
                }

                break;  

            case 'EDIT':

                if ($subject->getPartenaire()->getId())
                {
                    $part_id = (int)$subject->getPartenaire()->getId();

                }

                if($role==='ROLE_SUPER_ADMIN' && $profile==='ADMIN'){

                    return true;
 
                }elseif(($role==='ROLE_SUPER_ADMIN' || $role==='ROLE_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE'))
                {
                    return true;

                }elseif(($role==='ROLE_PARTENAIRE' && $profile==='ADMIN_PARTENAIRE') && ($part_id_connecte === $part_id))
                {
                    return true;

                }elseif(($role==='ROLE_PARTENAIRE' || $role==='ROLE_ADMIN_PARTENAIRE') && 
                    ($profile==='CAISSIER_PARTENAIRE' && $part_id_connecte === $part_id))
                {
                    return true;
                }
                break;

            case 'GET':

                if($role==='ROLE_SUPER_ADMIN' && $profile==='ADMIN'){

                    return true;
 
                }elseif(($role==='ROLE_SUPER_ADMIN' || $role==='ROLE_ADMIN') && ($profile==='CAISSIER' || $profile==='PARTENAIRE')){
                     
                    return true;
 
                }elseif($role==='ROLE_PARTENAIRE' && $profile==='ADMIN_PARTENAIRE'){
 
                    return true;
 
                }elseif(($role==='ROLE_PARTENAIRE' || $role==='ROLE_ADMIN_PARTENAIRE') && ($profile==='CAISSIER_PARTENAIRE')){
                     
                    return true;
 
                }
                break;
        }

        return false;
    }
}
