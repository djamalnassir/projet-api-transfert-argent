<?php

namespace App\Controller;


use Exception;
use App\Entity\Affectation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Annotation\Route;

class AffectationController extends AbstractController
{

  private $token;

  public function __construct (TokenStorageInterface $token)
  {
    $this->token = $token;
  }


  public function __invoke(Affectation $data)
  {

    $userConnecte = $this->token->getToken()->getUser();

    // Récuperation de l'ID_PARTENAIRE du USER qui effectue l'affectation
    $idPartenaireUserConnecte = $userConnecte->getPartenaire()->getId();

    // Récuperation de l'ID_PARTENAIRE a qui appartient le COMPTE à affecter
    $idPartenaireCompteAffectation = $data->getCompte()->getPartenaire()->getId();

    // Récuperation de l'ID_PARTENAIRE du USER a qui on effectue l'affectation
    $idPartenaireUserAffecte = $data->getUserAffecte()->getPartenaire()->getId();

    if($idPartenaireUserConnecte !== $idPartenaireCompteAffectation)
    {
      throw new Exception("Le compte ne vous appartient pas ...");

    }elseif($idPartenaireUserConnecte !== $idPartenaireUserAffecte)
    {
      throw new Exception("Le utilisateur ne fait pas parti de votre agence ...");
    }elseif($idPartenaireCompteAffectation !== $idPartenaireUserAffecte)
    {
      throw new Exception("Il ne s'agit pas de la même agence ...");

    }else 
    {
      $data->setUserAffecteur($userConnecte);
    }

    return $data;

  }
        
}
