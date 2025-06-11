<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PurchaseService
{
    public function __construct(
        private NotificationService $notificationService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function canPurchase(User $user, Product $product): bool
    {
        return $user->isActif() && $user->getPoints() >= $product->getPrice();
    }

    public function purchase(User $user, Product $product): bool
    {
        if (!$this->canPurchase($user, $product)) {
            return false;
        }

        $user->setPoints($user->getPoints() - $product->getPrice());
        
        // Notifier l'admin
        $this->notificationService->notifyAdmin(
            'Achat du produit',
            $product->getName() . ' par ' . $user->getFirstname() . ' ' . $user->getLastname(),
            new \DateTimeImmutable()
        );

        return true;
    }
}
