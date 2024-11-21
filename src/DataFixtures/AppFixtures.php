<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\Player;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("user@scores-api.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "secret"));
        $manager->persist($user);

        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@scores-api.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "secret"));
        $manager->persist($userAdmin);


        $faker = Factory::create('fr_FR');

        $teams = [];
        for ($i = 0; $i < 20; $i++) {
            $team = new Team();
            $team->setName($faker->city);
            $team->setScore(rand(0,100));
            $manager->persist($team);

            $teams[] = $team;
        }

        for ($i = 0; $i < 100; $i++) {
            $player = new Player();
            $player->setFirstName($faker->firstNameMale());
            $player->setLastName($faker->lastName);
            $player->setGoalCount(rand(0,30));

            $team = $teams[array_rand($teams)];
            $player->setTeam($team);

            $manager->persist($player);
        }

        $manager->flush();
    }
}

