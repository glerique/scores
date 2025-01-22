<?php

namespace App\Controller;

use App\Entity\Player;
use App\Service\PlayerService;
use App\Repository\TeamRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerController extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly PlayerService $playerService,
    ) {}

    #[Route('/api/player', name:"createPlayer", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour créer un joueur')]
    public function create(Request $request): JsonResponse
    {
        $player = $this->playerService->createPlayer($request);

        $jsonPlayer = $this->serializer->serialize($player, 'json', ['groups' => 'getPlayers']);
        $location = $this->urlGenerator->generate('detailPlayer', ['id' => $player->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonPlayer, Response::HTTP_CREATED, ["Location" => $location], true);
   }

   #[Route('/api/player/{id}', name:"updatePlayer", methods:['PUT'])]
   #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour modifier un joueur')]
    public function update(Request $request, #[MapEntity] Player $player): JsonResponse
    {
        $this->playerService->updatePlayer($request, $player);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }

    #[Route('/api/player/{id}', name: 'detailPlayer', methods: ['GET'])]
    public function getDetail(#[MapEntity] Player $player): JsonResponse
    {
        $jsonBook = $this->serializer->serialize($player, 'json', ['groups' => 'getPlayers']);
        return new JsonResponse($jsonBook, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/player/{id}', name: 'deletePlayer', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour supprimer un joueur')]
    public function delete(Player $player, EntityManagerInterface $em): JsonResponse
    {
        $this->playerService->deletePlayer($player);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/players', name: 'player', methods: ['GET'])]
    public function getList(): JsonResponse
    {
        $playerList =  $this->playerService->getAllPlayers();
        $jsonPlayerList = $this->serializer->serialize($playerList, 'json', ['groups' => 'getPlayers']);
        return new JsonResponse($jsonPlayerList, Response::HTTP_OK, [], true);
    }
}

