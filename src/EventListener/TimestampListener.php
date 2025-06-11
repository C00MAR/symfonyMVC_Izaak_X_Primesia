<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class TimestampListener
{
    #[AsEventListener(event: 'doctrine.pre_persist')]
    public function onDoctrinePrePersist($event): void
    {
        // ...
    }
}
