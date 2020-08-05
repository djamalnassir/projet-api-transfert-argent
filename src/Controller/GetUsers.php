<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GetUsers extends AbstractController
{
   
  private $repository;
  private $token;

  public function __construct(UserRepository $repository, TokenStorageInterface $token)
  {
    $this->repository = $repository;
    $this->token = $token;
  }

  /**
	* @Route("/api/users/getUsers", name="get.users")
	*/
  public function getUsers(): User
  {
    $data = $this->repository->findAll();
    $userToken = $this->token->getToken()->getUser();

    $profile = $userToken->getProfile()->getLibell();
    $user = [];

    if (strcmp($profile, "SUPER_ADMIN") == 0){

      foreach($data as $user){

        if(strcmp($user->getProfile()->getLibelle(), "SUPER_ADMIN") != 0){
          
          array_push($users, $user);
        }
      }
    }elseif (strcmp($profile, "ADMIN") == 0){

      foreach( $data as $user ){

        if(strcmp($user->getProfile()->getLibelle(), "ADMIN") != 0){
          
          array_push($users, $user);
        }
      }
    }

    return $users;
  }
}
