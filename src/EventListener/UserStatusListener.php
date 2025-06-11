<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\NotificationService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::preUpdate)]
final class UserStatusListener
{
    public function __construct(
        private NotificationService $notificationService,
    ) {
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof User && $args->hasChangedField('actif')) {
            $newValue = $args->getNewValue('actif');
            
            if (!$newValue) { // User désactivé
                $this->notificationService->notifyUser(
                    $entity->getId(),
                    'Votre compte a été désactivé le ' . (new \DateTimeImmutable())->format('d/m/Y H:i')
                );
            }
        }
    }
}
