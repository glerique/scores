<?php

namespace App\DataFixtures;


use App\Entity\Game;
use DateTimeImmutable;
use App\Service\TeamService;
use App\DataFixtures\TeamFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AbstractBasicFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GameFixtures extends AbstractBasicFixtures implements DependentFixtureInterface
{
    private const GAMES_COUNT = 100;

    public function __construct(
        private TeamService $teamService
    ){
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::GAMES_COUNT; $i++) {
            $game = new Game();
            $dateTime = $this->faker->dateTimeBetween('-6 months', 'now');
            $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);
            $game->setPlayedAt($dateTimeImmutable);
            $game->setHomeTeam($this->getReference('team_' . $this->faker->numberBetween(0, 19)));
            $game->setAwayTeam($this->getReference('team_' . $this->faker->numberBetween(0, 19)));
            $game->setHomeScore($this->faker->numberBetween(0, 10));
            $game->setAwayScore($this->faker->numberBetween(0, 10));
            $manager->persist($game);

            $this->addReference('game_' . $i, $game);
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