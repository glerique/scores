<?php

namespace App\Entity;

use App\Entity\PlayerStats;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getPlayers", "getTeams"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getPlayers", "getTeams"])]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(min: 3, max: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getPlayers", "getTeams"])]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(min: 3, max: 50)]
    private ?string $lastName = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getPlayers"])]
    #[Assert\NotNull()]
    private ?Team $team = null;

    /**
     * @var Collection<int, PlayerStats>
     */
    #[ORM\OneToMany(targetEntity: PlayerStats::class, mappedBy: 'player')]
    private Collection $playerStats;

    public function __construct()
    {
        $this->playerStats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

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
            $playerStat->setPlayer($this);
        }

        return $this;
    }

    public function removePlayerStat(PlayerStats $playerStat): static
    {
        if ($this->playerStats->removeElement($playerStat)) {
            // set the owning side to null (unless already changed)
            if ($playerStat->getPlayer() === $this) {
                $playerStat->setPlayer(null);
            }
        }

        return $this;
    }

    public function getTotalGoals(): int
    {
        return $this->playerStats->reduce(function (int $total, PlayerStats $stats) {
            return $total + $stats->getGoalCount();
        }, 0);
    }

    public function getTotalAssists(): int
    {
        return $this->playerStats->reduce(function (int $total, PlayerStats $stats) {
            return $total + $stats->getAssistCount();
        }, 0);
    }
}
