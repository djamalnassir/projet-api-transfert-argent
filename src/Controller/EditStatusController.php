<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EditStatusController extends AbstractController
{
	private $token;
	private $repository;	

	public function __construct(TokenStorageInterface $token, UserRepository $repository)
	{
		$this->token = $token;
		$this->repository = $repository;
	}

	/**
	* @Route("/api/users/editStatus/{id}", name="edit.status", requirements={"id" = "\d+" })
	*/
	public function editStatus($id)
	{

		$userConnecte = $this->token->getToken()->getUser();
		$role = $userConnecte->getRoles()[0];

		// il s'agit du USER dont on veut changer le status
		$subject = $this->repository->find($id);

		// on récupère le profile du USER dont on veut le status 
		$profile = $subject->getProfile()->getLibelle();

		switch ($role) {

			case 'ROLE_SUPER_ADMIN':
				
				$status = ($subject->getIsActive()) ? false : true;
				$subject->setIsActive($status);
				break;

			case 'ROLE_ADMIN':

				if($subject->getProfile->getLibelle() !== "SUPER_ADMIN" && $subject->getProfile->getLibelle() !== "ADMIN"){

					$status = ($subject->getIsActive()) ? false : true;
					$subject->setIsActive($status);

				}else
				{

					throw new Exception("VOUS NE POUVEZ PAS MODIFIER LE STATUT DE CET UTILISATEUR");
				}
				break;

			case 'ROLE_PARTENAIRE':
				
				if($subject->getProfile->getLibelle() !== "SUPER_ADMIN" && $subject->getProfile->getLibelle() !== "ADMIN" && 
				$subject->getProfile()->getLibelle() !== "CAISSIER")
				{

					$status = ($subject->getIsActive()) ? false : true;
					$subject->setIsActive($status);

				}else
				{

					throw new Exception("VOUS NE POUVEZ PAS MODIFIER LE STATUT DE CET UTILISATEUR");
				}
				break;

			case 'ROLE_ADMIN_PARTENAIRE':
				
				if($subject->getProfile->getLibelle() !== "SUPER_ADMIN" && $subject->getProfile->getLibelle() !== "ADMIN" &&
				   $subject->getProfile->getLibelle() !== "CAISSIER" && $subject->getProfile->getLibelle() !== "PARTENAIRE")
				{

					$status = ($subject->getIsActive()) ? false : true;
					$subject->setIsActive($status);

				}else
				{

					throw new Exception("VOUS NE POUVEZ PAS MODIFIER LE STATUT DE CET UTILISATEUR");
				}
				break;
		}


	}
}
