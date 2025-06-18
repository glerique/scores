<?php

namespace App\Controller;

use App\Entity\Team;
use App\Manager\TeamManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TeamController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TeamManager $teamManager
    ) {}
    
    #[Route('/api/team', name:"createTeam", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour créer une équipe')]
    public function createTeam(Request $request): JsonResponse
    {
        try {
            $team = $this->teamManager->createTeam($request);
            
            $jsonTeam = $this->serializer->serialize($team, 'json', ['groups' => 'getTeams']);
            $location = $this->urlGenerator->generate('detailTeam', ['id' => $team->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

            return new JsonResponse($jsonTeam, Response::HTTP_CREATED, ["Location" => $location], true);
            
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur interne du serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/team/{id}', name: 'detailTeam', methods: ['GET'])]
    public function getDetailTeam(#[MapEntity] Team $team): JsonResponse
    {
        $jsonTeam = $this->serializer->serialize($team, 'json', ['groups' => 'getTeams']);
        return new JsonResponse($jsonTeam, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/team/{id}', name:"updateTeam", methods:['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour modifier une équipe')]
    public function updateTeam(Request $request, Team $team): JsonResponse 
    {
        try {
            $this->teamManager->updateTeam($request, $team);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
            
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur interne du serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/team/{id}', name: 'deleteTeam', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour supprimer une équipe')]
    public function deleteTeam(Team $team): JsonResponse
    {
        try {
            $this->teamManager->deleteTeam($team);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
            
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la suppression'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/teams', name: 'team', methods: ['GET'])]
    public function getTeamList(): JsonResponse
    {
        $teamList = $this->teamManager->getAllTeams();
        $jsonTeamList = $this->serializer->serialize($teamList, 'json', ['groups' => 'getTeams']);

        return new JsonResponse($jsonTeamList, Response::HTTP_OK, [], true);
    }
}
