<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(min: 3, max: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(min: 3, max: 50)]
    private ?string $lastName = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0])]
    #[Assert\NotNull(message: "Le nombre de buts est obligatoire.")]
    #[Assert\Range(min: 0,max: 100,
    minMessage: "Le nombre de buts ne peut pas être inférieur à {{ limit }}.",
    maxMessage: "Le nombre debuts ne peut pas être supérieur à {{ limit }}.")]
    private ?int $goalCount = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
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
