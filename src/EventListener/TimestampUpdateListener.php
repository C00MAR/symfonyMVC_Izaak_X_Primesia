<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class TimestampUpdateListener
{
    #[AsEventListener(event: 'doctrine.pre_update')]
    public function onDoctrinePreUpdate($event): void
    {
        // ...
    }
}
