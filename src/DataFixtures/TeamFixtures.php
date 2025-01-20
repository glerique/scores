<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AbstractBasicFixtures;

class TeamFixtures extends AbstractBasicFixtures
{
    private const TEAMS_COUNT = 20;

    public function __construct()
    {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::TEAMS_COUNT; $i++) {
            $team = new Team();
            $team->setName($this->faker->city);
            $team->setPoints($this->faker->numberBetween(0, 100));
            $manager->persist($team);

            $this->addReference('team_' . $i, $team);
        }

        $manager->flush();
    }
}