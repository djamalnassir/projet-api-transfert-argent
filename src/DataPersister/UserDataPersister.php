<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{

    private $entity;
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entity, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entity = $entity;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }


    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {

        if ($data->getPassword()) {

            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPassword())
            );
            $data->eraseCredentials();
        }

        $this->entity->persist($data);
        $this->entity->flush();
    }

    public function remove($data)
    {
        $this->entity->remove($data);
        $this->entity->flush();
    }


}
