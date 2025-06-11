<?php

namespace App\MessageHandler;

use App\Message\NotificationMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class NotificationMessageHandler
{
    public function __invoke(NotificationMessage $message): void
    {
        // do something with your message
    }
}
