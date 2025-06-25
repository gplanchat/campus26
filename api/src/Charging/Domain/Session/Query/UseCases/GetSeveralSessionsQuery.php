<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Query\UseCases;

final readonly class GetSeveralSessionsQuery
{
    public function __construct(
        public int $currentPage = 1,
        public int $pageSize = 25
    ) {
    }
}
