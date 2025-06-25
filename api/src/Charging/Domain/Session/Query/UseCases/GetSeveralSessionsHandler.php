<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Query\UseCases;

use App\Charging\Domain\Session\Query\SessionPage;
use App\Charging\Domain\Session\Query\SessionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetSeveralSessionsHandler
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
    ) {}

    public function __invoke(GetSeveralSessionsQuery $query): SessionPage
    {
        return $this->sessionRepository->list($query->currentPage, $query->pageSize);
    }
}
