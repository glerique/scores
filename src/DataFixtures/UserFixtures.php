<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AbstractBasicFixtures;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends AbstractBasicFixtures
{
    private const USERS = [
        ['email' => 'user@scores-api.com', 'roles' => ['ROLE_USER']],
        ['email' => 'admin@scores-api.com', 'roles' => ['ROLE_ADMIN']]
    ];

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'secret'));

            $manager->persist($user);
        }

        $manager->flush();
    }
}