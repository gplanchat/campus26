<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Query\UseCases;

use App\Charging\Domain\Session\Query\Session;
use App\Charging\Domain\Session\Query\SessionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetOneSessionHandler
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
    ) {}

    public function __invoke(GetOneSessionQuery $query): Session
    {
        return $this->sessionRepository->get($query->sessionId);
    }
}
