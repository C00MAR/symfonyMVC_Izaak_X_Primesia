<?php

namespace App\Service;

use App\Message\NotificationMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationService
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function notifyAdmin(string $action, string $entityName, \DateTimeInterface $date): void
    {
        // Récupérer l'admin (supposons qu'il a l'ID 1)
        $adminId = 1; // À adapter selon votre logique
        $label = sprintf('%s %s le %s', $action, $entityName, $date->format('d/m/Y H:i'));
        
        $this->messageBus->dispatch(new NotificationMessage($adminId, $label));
    }

    public function notifyUser(int $userId, string $message): void
    {
        $this->messageBus->dispatch(new NotificationMessage($userId, $message));
    }
}
