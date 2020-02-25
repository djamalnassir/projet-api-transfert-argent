<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Repository\CompteRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DepotController extends AbstractController
{
    private $token;
    private $repository;

    public function __construct(TokenStorageInterface $token, CompteRepository $repository)
    {
        $this->token = $token;
        $this->repository = $repository;
    }

    public function __invoke(Depot $data): Depot
    {

        $userConnecte = $this->token->getToken()->getUser();

        if($data->getMontantDepot() >= 50){

            $data->setUser($userConnecte);

            $compte = $this->repository->findOneByNumeroCompte($data->getCompte()->getNumeroCompte());
            
            $nouveauSolde = $data->getMontantDepot() + $compte->getSoldeCompte();

            $compte->setSoldeCompte($nouveauSolde);

            $data->setCompte($compte);

        }else{
            throw new Exception("le montant du dépôt doit etre superieur à 50frs ");
        }
        
        return $data;
    }
}
