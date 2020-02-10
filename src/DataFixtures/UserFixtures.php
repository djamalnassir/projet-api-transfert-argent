<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Profile SUPERADMIN
        $role_superadmin = new Profile();
        $role_superadmin->setLibelle("SUPER_ADMIN");
        $manager->persist($role_superadmin);

        // Profile ADMIN
        $role_admin = new Profile();
        $role_admin->setLibelle("ADMIN");
        $manager->persist($role_admin);

        // Profile CAISSIER
        $role_caissier = new Profile();
        $role_caissier->setLibelle("CAISSIER");
        $manager->persist($role_caissier);

        

        // Profile PARTENAIRE
        $role_partenaire = new Profile();
        $role_partenaire->setLibelle("PARTENAIRE");
        $manager->persist($role_partenaire);

        $manager->flush();

        /*
            $this->addReference('role_super_admin', $role_superadmin);
            $this->addReference('role_admin', $role_admin);
            $this->addReference('role_caissier', $role_caissier);

            $role_super_admin = $this->getReference('role_super_admin');

            dd($user->getRoles());
        */

        

        $user = new User();

        $user->setUsername("root")
             ->setPassword($this->encoder->encodePassword($user, "root"))
             ->setProfile($role_superadmin);

        $manager->persist($user);

        $manager->flush();
    }
}
