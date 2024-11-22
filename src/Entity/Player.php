<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
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

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0])]
    #[Groups(["getPlayers", "getTeams"])]
    #[Assert\NotNull(message: "Le nombre de buts est obligatoire.")]
    private ?int $goalCount = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getPlayers"])]
    #[Assert\NotNull()]
    private ?Team $team = null;

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

    public function getGoalCount(): ?int
    {
        return $this->goalCount;
    }

    public function setGoalCount(?int $goalCount): static
    {
        $this->goalCount = $goalCount;

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
}

