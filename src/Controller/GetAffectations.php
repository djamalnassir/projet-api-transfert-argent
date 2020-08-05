<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Repository\AffectationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GetAffectations extends AbstractController
{
   
  private $repository;
  private $token;

  public function __construct(AffectationRepository $repository, TokenStorageInterface $token)
  {
    $this->repository = $repository;
    $this->token = $token;
  }

  /**
	* @Route("/api/affectations/getAffectations", name="get.affectations")
	*/
  public function getAffectations()
  {

    $affectations = array();

    $data = $this->repository->findAll();

    $userToken = $this->token->getToken()->getUser();

    $profile = $userToken->getProfile()->getLibelle(); // profile de l'utilisateur connecté.

    $partenaire_id = $userToken->getPartenaire()->getId(); // identifiant de l'utilisateur connecté.

    foreach($data as $affectation){

      /*if($affectation->getUserAffecteur()->getPartenaire()->getId() == $partenaire_id){
        
        array_push($affectations, $affectation);
      }*/
      $affectations[] = array(
        'id' => $affectation->getId(),
        'dateDebut' => $affectation->getDateDebut()
        
      );
    }

    return new JsonResponse($affectations);

    // return new Response(json_encode($affectations), 200);

  }
}
