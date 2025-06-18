<?php

namespace App\Manager;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class TeamManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
        private readonly TeamRepository $teamRepository,
        private readonly ValidatorInterface $validator
    ) {}

    public function createTeam(Request $request): Team
    {
        $content = $request->getContent();
        if (empty($content)) {
            throw new \InvalidArgumentException('Le contenu de la requête ne peut pas être vide');
        }

        try {
            $team = $this->serializer->deserialize($content, Team::class, 'json');
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Format JSON invalide');
        }
        
        $errors = $this->validator->validate($team);
        if ($errors->count() > 0) {
            $errorMessages = $this->getValidationErrors($errors);
            throw new \InvalidArgumentException(implode(', ', $errorMessages));
        }

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return $team;
    }

    public function updateTeam(Request $request, Team $team): void
    {
        $content = $request->getContent();
        if (empty($content)) {
            throw new \InvalidArgumentException('Le contenu de la requête ne peut pas être vide');
        }

        try {
            $this->serializer->deserialize(
                $content, 
                Team::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $team]
            );
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Format JSON invalide');
        }
        
        $errors = $this->validator->validate($team);
        if ($errors->count() > 0) {
            $errorMessages = $this->getValidationErrors($errors);
            throw new \InvalidArgumentException(implode(', ', $errorMessages));
        }

        $this->entityManager->flush();
    }

    public function deleteTeam(Team $team): void
    {
        $this->entityManager->remove($team);
        $this->entityManager->flush();
    }

    public function getAllTeams(): array
    {
        return $this->teamRepository->findAll();
    }
    
    private function getValidationErrors(ConstraintViolationListInterface $errors): array
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
        }
        return $errorMessages;
    }
}