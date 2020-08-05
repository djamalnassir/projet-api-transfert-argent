<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProfileRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    private $token;
    private $repository;

    public function __construct(TokenStorageInterface $token, ProfileRepository $repository)
    {
        $this->token = $token;
        $this->repository = $repository;
    }

    public function __invoke(User $data): User
    {

        if($this->token->getToken()->getUser()->getRoles()[0] === 'ROLE_PARTENAIRE')
        {

            // $data->setPartenaire($this->token->getToken()->getUser()->getPartenaire());

            $part_id_connecte = (int)$this->token->getToken()->getUser()->getPartenaire()->getId();

        }

        if ($data->getPartenaire()->getId()) 
        {
            $part_id = (int)$data->getPartenaire()->getId();
        }

        if ($part_id !== $part_id_connecte)
        {
            throw new Exception("Cet utilisateur n'est pas de votre agence ...");
        }
        
        $profile = $this->repository->findOneByLibelle($data->getProfile()->getLibelle());
        
        $data->setProfile($profile);

        return $data;
    }

}
