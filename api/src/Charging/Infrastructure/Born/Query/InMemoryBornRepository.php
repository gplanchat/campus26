<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Born\Query;

use App\Charging\Domain\Born\BornId;
use App\Charging\Domain\Born\Query\Born;
use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Born\Query\BornRepositoryInterface;
use App\Platform\Collection\Collection;

final class InMemoryBornRepository implements BornRepositoryInterface
{
    /**
     * @param Born[] $payments
     */
    public function __construct(
        private array $payments = []
    ) {
    }

    public static function withTestingFixtures(): self
    {
        return new self([
            new Born(BornId::fromString('0197a144-0062-73ab-9b14-a8fdf3026852')),
            new Born(BornId::fromString('0197a144-0062-7a85-b01c-1e07b9a92f37')),
            new Born(BornId::fromString('0197a144-0062-7985-8c94-a5685f3e9a64')),
            new Born(BornId::fromString('0197a144-0062-7160-a9e4-4bda2dd7dadf')),
            new Born(BornId::fromString('0197a144-0062-7bbb-854f-cce2c044b5cc')),
            new Born(BornId::fromString('0197a144-0062-7fef-8cad-32139118efda')),
            new Born(BornId::fromString('0197a144-0062-7d71-8027-84e37bc453c6')),
            new Born(BornId::fromString('0197a144-0062-73e7-aaaa-de205fc1ba56')),
            new Born(BornId::fromString('0197a144-0062-7907-9c12-ada241d7012d')),
            new Born(BornId::fromString('0197a144-0062-7f93-8c5c-51727faf4a99')),
        ]);
    }

    public function get(BornId $id): Born
    {
        $result = Collection::fromArray($this->payments)
            ->filter(fn (Born $current) => $current->id->equals($id))
            ->limit(1)
            ->toArray();

        if (count($result) <= 0) {
            throw new NotFoundException();
        }

        return array_shift($result);
    }
}
