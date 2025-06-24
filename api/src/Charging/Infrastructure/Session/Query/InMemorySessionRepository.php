<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Session\Query;

use App\Charging\Domain\Session\NotFoundException;
use App\Charging\Domain\Session\Query\Session;
use App\Charging\Domain\Session\Query\SessionPage;
use App\Charging\Domain\Session\Query\SessionRepositoryInterface;
use App\Charging\Domain\Session\SessionId;
use App\Platform\Collection\Collection;

final class InMemorySessionRepository implements SessionRepositoryInterface
{
    /**
     * @param Session[] $sessions
     */
    public function __construct(
        private array $sessions = []
    ) {
    }

    public static function withTestingFixtures(): self
    {
        return new self([
            new Session(SessionId::createRandom()),
            new Session(SessionId::createRandom()),
            new Session(SessionId::createRandom()),
            new Session(SessionId::createRandom()),
            new Session(SessionId::createRandom()),
            new Session(SessionId::createRandom()),
            new Session(SessionId::createRandom()),
            new Session(SessionId::createRandom()),
            new Session(SessionId::createRandom()),
        ]);
    }

    public function get(SessionId $id): Session
    {
        $result = Collection::fromArray($this->sessions)
            ->filter(fn (Session $current) => $current->id->equals($id))
            ->limit(1)
            ->toArray();

        if (count($result) <= 0) {
            throw new NotFoundException();
        }

        return array_shift($result);
    }

    public function list(int $currentPage = 1, int $pageSize = 25): SessionPage
    {
        $collection = Collection::fromArray($this->sessions)
            ->offset(($currentPage - 1) * $pageSize)
            ->limit($pageSize);

        return new SessionPage(
            $currentPage,
            $pageSize,
            count($this->sessions),
            ...$collection
        );
    }
}
