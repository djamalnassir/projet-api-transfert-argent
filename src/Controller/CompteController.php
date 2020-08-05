<?php

namespace App\Controller;

use App\Entity\Compte;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CompteController extends AbstractController
{
    private $token;
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, TokenStorageInterface $token)
    {
        $this->token = $token;
        $this->encoder = $encoder;
    }

    public function __invoke(Compte $data): Compte
    {
        
        $userConnecte = $this->token->getToken()->getUser();

        $data->setUserCreateur($userConnecte);

        $montantDepot = $data->getDepots()[0]->getMontantDepot();

        $data->getDepots()[0]->setUser($userConnecte);
        
        $data->getDepots()[0]->setCompte($data);

        if($montantDepot < 500000)
            throw new Exception("le solde doit etre superieur ou égal a 500000");
        else
            $data->setSoldeCompte($montantDepot);
        
        return $data;
    }
}
