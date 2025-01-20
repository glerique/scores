<?php

namespace App\DataFixtures;

use App\Entity\PlayerStats;
use App\DataFixtures\TeamFixtures;
use App\DataFixtures\PlayerFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AbstractBasicFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlayerStatsFixtures extends AbstractBasicFixtures implements DependentFixtureInterface
{
    private const PLAYER_STATS_COUNT = 100;

    public function __construct()
    {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < self::PLAYER_STATS_COUNT; $i++) {
            $playerStats = new PlayerStats();
            $playerStats->setPlayer($this->getReference('player_' . $this->faker->numberBetween(0, 19)));
            $playerStats->setGame($this->getReference('game_' . $this->faker->numberBetween(0, 99)));
            $playerStats->setGoalCount($this->faker->numberBetween(0, 10));
            $playerStats->setAssistCount($this->faker->numberBetween(0, 10));
            $manager->persist($playerStats);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PlayerFixtures::class,
            TeamFixtures::class,
        ];
    }
}
