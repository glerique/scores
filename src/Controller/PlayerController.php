<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\TeamRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerController extends AbstractController
{
    #[Route('/api/player', name:"createPlayer", methods: ['POST'])]
    public function createPlayer(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator,
    TeamRepository $teamRepository): JsonResponse
    {
        $player = $serializer->deserialize($request->getContent(), Player::class, 'json');
        $content = $request->toArray();
        $id = $content['team'] ?? -1;
        $player->setTeam($teamRepository->find($id));

        $em->persist($player);
        $em->flush();

        $jsonPlayer = $serializer->serialize($player, 'json', ['groups' => 'getPlayers']);
        $location = $urlGenerator->generate('detailPlayer', ['id' => $player->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonPlayer, Response::HTTP_CREATED, ["Location" => $location], true);
   }

   #[Route('/api/player/{id}', name:"updatePlayer", methods:['PUT'])]
    public function updatePlayer(Request $request, SerializerInterface $serializer, Player $player, EntityManagerInterface $em, TeamRepository $teamRepository): JsonResponse
    {
        $updatedPlayer = $serializer->deserialize($request->getContent(), Player::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $player]);
        $content = $request->toArray();

        $id = $content['team'] ?? -1;
        $updatedPlayer->setTeam($teamRepository->find($id));

        $em->persist($updatedPlayer);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }

    #[Route('/api/player/{id}', name: 'detailPlayer', methods: ['GET'])]
    public function getDetailPlayer(#[MapEntity] Player $player, SerializerInterface $serializer, PlayerRepository $playerRepository): JsonResponse
    {
        $jsonBook = $serializer->serialize($player, 'json', ['groups' => 'getPlayers']);
        return new JsonResponse($jsonBook, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/player/{id}', name: 'deletePlayer', methods: ['DELETE'])]
    public function deletePlayer(Player $player, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($player);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/players', name: 'player', methods: ['GET'])]
    public function getPlayerList(PlayerRepository $playerRepository, SerializerInterface $serializer): JsonResponse
    {
        $playerList = $playerRepository->findAll();
        $jsonPlayerList = $serializer->serialize($playerList, 'json', ['groups' => 'getPlayers']);
        return new JsonResponse($jsonPlayerList, Response::HTTP_OK, [], true);
    }
}

