<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Session\DataFixtures;

use App\Charging\Domain\Born\BornId;
use App\Charging\Domain\Payment\PaymentId;
use App\Charging\Domain\Session\Query\Session;
use App\Charging\Domain\Session\SessionId;
use App\Charging\Infrastructure\StorageMock;
use Psr\Clock\ClockInterface;
use Symfony\Contracts\Cache\ItemInterface;

final readonly class SessionDataFixtures
{
    public function __construct(
        private StorageMock $cache,
    ) {
    }

    public function load(ClockInterface $clock): void
    {
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-7bb8-81b0-31b2349f6abe'),
                bornId: BornId::fromString('0197a144-0062-73ab-9b14-a8fdf3026852'),
                paymentId: PaymentId::fromString('0197a142-47ee-76c5-bbc9-0b290166e1fa'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-78f5-bcee-d3f1f4817854'),
                bornId: BornId::fromString('0197a144-0062-7a85-b01c-1e07b9a92f37'),
                paymentId: PaymentId::fromString('0197a142-47ee-7041-97a2-0bc76b1c0c73'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-7023-a708-fe4e83b4b180'),
                bornId: BornId::fromString('0197a144-0062-7985-8c94-a5685f3e9a64'),
                paymentId: PaymentId::fromString('0197a142-47ee-79c4-8fa4-8b00729db8f5'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-7b84-9204-aad08d90be62'),
                bornId: BornId::fromString('0197a144-0062-7160-a9e4-4bda2dd7dadf'),
                paymentId: PaymentId::fromString('0197a142-47ee-7b5d-9c20-ef27227a3a7e'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-71c4-abc2-3d95324bc3c5'),
                bornId: BornId::fromString('0197a144-0062-7bbb-854f-cce2c044b5cc'),
                paymentId: PaymentId::fromString('0197a142-47ee-7cc8-ad1a-26b4d8178504'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-708c-a13a-24754318798f'),
                bornId: BornId::fromString('0197a144-0062-7fef-8cad-32139118efda'),
                paymentId: PaymentId::fromString('0197a142-47ee-7809-8797-ed99fd43958d'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-74a9-ab6d-7ebda06ad5ed'),
                bornId: BornId::fromString('0197a144-0062-7d71-8027-84e37bc453c6'),
                paymentId: PaymentId::fromString('0197a142-47ee-7144-925f-e07f83a4a2b8'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-7641-9693-bfd42dc3d498'),
                bornId: BornId::fromString('0197a144-0062-73e7-aaaa-de205fc1ba56'),
                paymentId: PaymentId::fromString('0197a142-47ef-7023-9039-42c09d7aef83'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-7184-b65c-e7c432a07512'),
                bornId: BornId::fromString('0197a144-0062-7907-9c12-ada241d7012d'),
                paymentId: PaymentId::fromString('0197a142-47ef-79ef-9287-a29ec8bebea0'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
        $this->with(
            new Session(
                SessionId::fromString('0197a118-e6af-7b58-aca1-6acc53c402b1'),
                bornId: BornId::fromString('0197a144-0062-7f93-8c5c-51727faf4a99'),
                paymentId: PaymentId::fromString('0197a142-47ef-7908-8978-2f82003ad410'),
                startingDateTime: \DateTimeImmutable::createFromInterface($clock->now())->sub(new \DateInterval('P1D')),
            )
        );
    }

    public function unload(): void
    {
        $this->cache->invalidateTags(['session']);
    }

    private function with(Session $session): void
    {
        $this->cache->get(self::getCacheKey($session->id), function (ItemInterface $cacheItem) use ($session) {
            $cacheItem->tag(['session']);

            return $session;
        });
    }

    public static function getCacheKey(SessionId $sessionId): string
    {
        return sprintf('session.%s', $sessionId->toString());
    }
}
