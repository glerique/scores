<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TeamController extends AbstractController
{
    #[Route('/api/team', name:"createTeam", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour créer une équipe')]
    public function createTeam(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator,
    ValidatorInterface $validator): JsonResponse
    {
        $team = $serializer->deserialize($request->getContent(), Team::class, 'json');

        $errors = $validator->validate($team);

        if ($errors->count() > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $em->persist($team);
        $em->flush();

        $jsonTeam = $serializer->serialize($team, 'json', ['groups' => 'getTeams']);
        $location = $urlGenerator->generate('detailTeam', ['id' => $team->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonTeam, Response::HTTP_CREATED, ["Location" => $location], true);
   }

    #[Route('/api/team/{id}', name: 'detailTeam', methods: ['GET'])]
    public function getDetailTeam(#[MapEntity] Team $team, SerializerInterface $serializer, TeamRepository $teamRepository): JsonResponse
    {
        $jsonTeam = $serializer->serialize($team, 'json', ['groups' => 'getTeams']);
        return new JsonResponse($jsonTeam, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/team/{id}', name:"updateTeam", methods:['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour modifier une équipe')]
    public function updateTeam(Request $request, SerializerInterface $serializer, Team $currentTeam, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse {

        $tempTeam = $serializer->deserialize($request->getContent(), Team::class, 'json');

        $errors = $validator->validate($tempTeam);
        if ($errors->count() > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $reflectionClass = new \ReflectionClass($tempTeam);
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $newValue = $property->getValue($tempTeam);

            if ($newValue !== null) {
                $setter = 'set' . ucfirst($property->getName());
                if (method_exists($currentTeam, $setter)) {
                    $currentTeam->$setter($newValue);
                }
            }
        }

        $em->persist($currentTeam);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

   #[Route('/api/team/{id}', name: 'deleteTeam', methods: ['DELETE'])]
   #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour supprimer une équipe')]
    public function deleteTeam(Team $team, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($team);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/teams', name: 'team', methods: ['GET'])]
    public function getTeamList(TeamRepository $teamRepository, SerializerInterface $serializer): JsonResponse
    {
        $teamList = $teamRepository->findAll();
        $jsonTeamList = $serializer->serialize($teamList, 'json', ['groups' => 'getTeams']);

        return new JsonResponse($jsonTeamList, Response::HTTP_OK, [], true);
    }
}

