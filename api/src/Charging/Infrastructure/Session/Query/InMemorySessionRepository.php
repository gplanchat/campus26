<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Session\Query;

use App\Authentication\Domain\Organization\Query\Organization;
use App\Authentication\Domain\Organization\Query\UseCases\OrganizationPage;
use App\Authentication\Domain\Realm\RealmId;
use App\Authentication\Infrastructure\Organization\DataFixtures\OrganizationFixtures;
use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Session\Query\Session;
use App\Charging\Domain\Session\Query\SessionPage;
use App\Charging\Domain\Session\Query\SessionRepositoryInterface;
use App\Charging\Domain\Session\SessionId;
use App\Charging\Infrastructure\Session\DataFixtures\SessionDataFixtures;
use App\Charging\Infrastructure\StorageMock;
use App\Platform\Collection\Collection;

final class InMemorySessionRepository implements SessionRepositoryInterface
{
    public function __construct(
        private StorageMock $storage,
    ) {
    }

    public function get(SessionId $id): Session
    {
        $item = $this->storage->getItem(SessionDataFixtures::getCacheKey($id));

        if (!$item->isHit()) {
            throw new NotFoundException();
        }

        $value = $item->get();
        if (!$value instanceof Session) {
            throw new NotFoundException();
        }

        return $value;
    }

    public function list(int $currentPage = 1, int $pageSize = 25): SessionPage
    {
        $result = $this->walk()
            ->offset(($currentPage - 1) * $pageSize)
            ->limit($pageSize)
            ->toArray()
        ;

        return new SessionPage(
            $currentPage,
            $pageSize,
            \count($result),
            ...array_values($result)
        );
    }

    public function walk(int $pageSize = 25): Collection
    {
        return $this->storage->getValues()
            ->filter(fn (mixed $value): bool => $value instanceof Session)
        ;
    }
}
