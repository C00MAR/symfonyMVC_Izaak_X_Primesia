<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class UserStatusListener
{
    #[AsEventListener(event: 'doctrine.post_update')]
    public function onDoctrinePostUpdate($event): void
    {
        // ...
    }
}
