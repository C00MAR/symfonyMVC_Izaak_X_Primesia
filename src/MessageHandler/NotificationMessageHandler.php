<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Entity\User;
use App\Message\NotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class NotificationMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(NotificationMessage $message): void
    {
        $user = $this->entityManager->getRepository(User::class)->find($message->userId);
        
        if (!$user) {
            return;
        }

        $notification = new Notification();
        $notification->setUser($user);
        $notification->setLabel($message->label);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}
