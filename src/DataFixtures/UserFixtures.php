<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;


class UserFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher) {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
//        $testUser = new User();
//        $testUser->setLastname("user");
//        $testUser->setFirstname("user");
//        $testUser->setEmail("user@user.fr");
//        $encodedPassword = $this->hasher->hashPassword($testUser,"user");
//        $testUser->setPassword($encodedPassword);
//        $testUser->setRoles(["ROLE_USER"]);

        $testRH = new User();
        $testRH->setEmail("rh@humanbooster.com");
        $encodedPassword = $this->hasher->hashPassword($testRH,"rh123@");
        $testRH->setPassword($encodedPassword);
        $testRH->setRoles(["ROLE_RH"]);

//        $manager->persist($testUser);
        $manager->persist($testRH);

        $manager->flush();
    }
}