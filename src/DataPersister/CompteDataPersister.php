<?php

namespace App\DataPersister;

use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompteDataPersister implements DataPersisterInterface
{

    private $manager;
    private $encoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
    }


    public function supports($data): bool
    {
        return $data instanceof Compte;
    }

    /**
     * @param Compte $data
     */
    public function persist($data)
    {
        $userPartenaire = $data->getPartenaire()->getUsers()[0];

        if ($userPartenaire->getPassword()) {

            $userPartenaire->setPassword(
                $this->encoder->encodePassword($userPartenaire, $userPartenaire->getPassword())
            );
        }

        $this->manager->persist($data);
        $this->manager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

}
