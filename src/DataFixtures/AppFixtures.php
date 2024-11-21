<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Team;
use App\Entity\Player;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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

