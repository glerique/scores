<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Team $homeTeam = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Team $awayTeam = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $playedAt = null;

    #[ORM\Column]
    private ?int $homeScore = null;

    #[ORM\Column]
    private ?int $awayScore = null;

    /**
     * @var Collection<int, PlayerStats>
     */
    #[ORM\OneToMany(targetEntity: PlayerStats::class, mappedBy: 'game')]
    private Collection $playerStats;

    public function __construct()
    {
        $this->playerStats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Team $homeTeam): static
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Team $awayTeam): static
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getPlayedAt(): ?\DateTimeImmutable
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeImmutable $playedAt): static
    {
        $this->playedAt = $playedAt;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(int $homeScore): static
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(int $awayScore): static
    {
        $this->awayScore = $awayScore;

        return $this;
    }

    /**
     * @return Collection<int, PlayerStats>
     */
    public function getPlayerStats(): Collection
    {
        return $this->playerStats;
    }

    public function addPlayerStat(PlayerStats $playerStat): static
    {
        if (!$this->playerStats->contains($playerStat)) {
            $this->playerStats->add($playerStat);
            $playerStat->setGame($this);
        }

        return $this;
    }

    public function removePlayerStat(PlayerStats $playerStat): static
    {
        if ($this->playerStats->removeElement($playerStat)) {
            // set the owning side to null (unless already changed)
            if ($playerStat->getGame() === $this) {
                $playerStat->setGame(null);
            }
        }

        return $this;
    }
}
