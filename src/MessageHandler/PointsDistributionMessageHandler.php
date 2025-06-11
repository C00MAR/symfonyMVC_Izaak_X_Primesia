<?php

namespace App\MessageHandler;

use App\Message\PointsDistributionMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PointsDistributionMessageHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(PointsDistributionMessage $message): void
    {
        $activeUsers = $this->userRepository->findBy(['actif' => true]);
        
        foreach ($activeUsers as $user) {
            $user->setPoints($user->getPoints() + $message->points);
        }
        
        $this->entityManager->flush();
    }
}
    