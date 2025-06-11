<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
final class PointsDistributionMessage
{
    public function __construct(
        public readonly int $points = 1000,
    ) {
    }
}
