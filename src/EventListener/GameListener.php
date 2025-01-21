<?php

namespace App\EventListener;

use App\Entity\Game;
use App\Service\TeamService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
class GameListener
{
    public function __construct(
        private TeamService $teamService
    ) {}

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->updatePoints($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->updatePoints($args);
    }

    private function updatePoints(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Game) {
            return;
        }

        $this->teamService->updateTeamPoints($entity);
        $args->getObjectManager()->flush();
    }
}