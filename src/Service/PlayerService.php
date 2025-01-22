<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\TeamRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PlayerService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
        private readonly TeamRepository $teamRepository,
        private readonly PlayerRepository $playerRepository
    ) {}

    public function createPlayer(Request $request): Player
    {
        $player = $this->serializer->deserialize($request->getContent(), Player::class, 'json');
        $content = $request->toArray();

        $teamId = $content['team'] ?? -1;
        $player->setTeam($this->teamRepository->find($teamId));

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $player;
    }

    public function updatePlayer(Request $request, Player $player): void
    {
        $updatedPlayer = $this->serializer->deserialize(
            $request->getContent(),
            Player::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $player]
        );

        $content = $request->toArray();
        $teamId = $content['team'] ?? -1;
        $updatedPlayer->setTeam($this->teamRepository->find($teamId));

        $this->entityManager->persist($updatedPlayer);
        $this->entityManager->flush();
    }

    public function deletePlayer(Player $player): void
    {
        $this->entityManager->remove($player);
        $this->entityManager->flush();
    }

    public function getAllPlayers(): array
    {
        return $this->playerRepository->findAll();
    }
}