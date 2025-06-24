<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Command;

use App\Charging\Domain\Session\SessionId;

final class Session
{
    public function __construct(
        private SessionId $id,
        private int $version = 0,
        private array $events = [],
    ) {}
}
