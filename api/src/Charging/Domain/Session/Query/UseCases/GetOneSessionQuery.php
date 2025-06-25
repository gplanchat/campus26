<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Query\UseCases;

use App\Charging\Domain\Session\SessionId;

final class GetOneSessionQuery
{
    public function __construct(
        public SessionId $sessionId,
    ) {
    }
}
