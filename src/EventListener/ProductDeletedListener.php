<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ProductDeletedListener
{
    #[AsEventListener(event: 'doctrine.post_remove')]
    public function onDoctrinePostRemove($event): void
    {
        // ...
    }
}
