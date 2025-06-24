<?php

namespace App\Charging\Domain\Session\Command;

use App\Charging\Domain\Session\SessionId;

interface SessionRepositoryInterface
{
    public function get(SessionId $sessionId): Session;
    public function save(Session $session): void;
}
