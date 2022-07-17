<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('Simon')
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                '123456'
            ))
        ;
        $manager->persist($user);
        $this->addReference(self::USER_REFERENCE, $user);
        $manager->flush();
    }
}
