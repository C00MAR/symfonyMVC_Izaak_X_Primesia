<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Service\NotificationService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsDoctrineListener(event: Events::postUpdate)]
final class ProductUpdatedListener
{
    public function __construct(
        private NotificationService $notificationService,
    ) {
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Product) {
            $this->notificationService->notifyAdmin(
                'Produit modifié',
                $entity->getName(),
                new \DateTimeImmutable()
            );
        }
    }
}
