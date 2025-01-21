<?php

namespace App\Service;

use App\Entity\Game;

class TeamService
{
    private const POINTS_VICTORY = 3;
    private const POINTS_DRAW = 1;

    public function updateTeamPoints(Game $game): void
    {
        $homeTeam = $game->getHomeTeam();
        $awayTeam = $game->getAwayTeam();

        $homeScore = $game->getHomeScore();
        $awayScore = $game->getAwayScore();

        if ($homeScore > $awayScore) {
            $homeTeam->setPoints($homeTeam->getPoints() + self::POINTS_VICTORY);
        } elseif ($homeScore < $awayScore) {
            $awayTeam->setPoints($awayTeam->getPoints() + self::POINTS_VICTORY);
        } else {
            $homeTeam->setPoints($homeTeam->getPoints() + self::POINTS_DRAW);
            $awayTeam->setPoints($awayTeam->getPoints() + self::POINTS_DRAW);
        }

    }
}
