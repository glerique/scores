<?php

namespace App\Entity;

use App\Repository\PlayerStatsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerStatsRepository::class)]
class PlayerStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'playerStats')]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'playerStats')]
    private ?Game $game = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $goalCount = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $assistCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getGoalCount(): ?int
    {
        return $this->goalCount;
    }

    public function setGoalCount(int $goalCount): static
    {
        $this->goalCount = $goalCount;

        return $this;
    }

    public function getAssistCount(): ?int
    {
        return $this->assistCount;
    }

    public function setAssistCount(int $assistCount): static
    {
        $this->assistCount = $assistCount;

        return $this;
    }
}
