<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ProductCreatedListener
{
    #[AsEventListener(event: 'doctrine.post_persist')]
    public function onDoctrinePostPersist($event): void
    {
        // ...
    }
}
