<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\DataFixtures\TeamFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AbstractBasicFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlayerFixtures extends AbstractBasicFixtures implements DependentFixtureInterface
{
    private const PLAYERS_COUNT = 100;

    public function __construct()
    {
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::PLAYERS_COUNT; $i++) {
            $player = new Player();
            $player->setFirstName($this->faker->firstNameMale());
            $player->setLastName($this->faker->lastName);
            $player->setTeam($this->getReference('team_' . $this->faker->numberBetween(0, 19)));
            $manager->persist($player);

            $this->addReference('player_' . $i, $player);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TeamFixtures::class,
        ];
    }
}