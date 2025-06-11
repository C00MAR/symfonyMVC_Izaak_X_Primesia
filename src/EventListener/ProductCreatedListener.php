<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Service\NotificationService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsDoctrineListener(event: Events::postPersist)]
final class ProductCreatedListener
{
    public function __construct(
        private NotificationService $notificationService,
    ) {
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Product) {
            $this->notificationService->notifyAdmin(
                'Produit créé',
                $entity->getName(),
                new \DateTimeImmutable()
            );
        }
    }
}
