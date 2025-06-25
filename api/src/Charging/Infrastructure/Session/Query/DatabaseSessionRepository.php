<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Session\Query;

use App\Charging\Domain\Born\BornId;
use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Payment\PaymentId;
use App\Charging\Domain\Session\Query\Session;
use App\Charging\Domain\Session\Query\SessionPage;
use App\Charging\Domain\Session\Query\SessionRepositoryInterface;
use App\Charging\Domain\Session\SessionId;
use App\Platform\Collection\Collection;
use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class DatabaseSessionRepository implements SessionRepositoryInterface
{
    public function __construct(
        #[Autowire('@db.connection')]
        private Connection $connection,
    ) {
    }
    public function get(SessionId $id): Session
    {
        $sql = <<<'SQL'
            SELECT id, born_id, payment_id, starting_date_time
            FROM charging_session
            WHERE id = :id
            LIMIT 1
            SQL;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':id', $id);

        $result = $statement->executeQuery();

        if ($result->rowCount()<= 0) {
            throw new NotFoundException();
        }

        $row = $result->fetchAssociative();

        return new Session(
            SessionId::fromString($row['id']),
            BornId::fromString($row['born_id']),
            PaymentId::fromString($row['payment_id']),
            \DateTimeImmutable::createFromFormat('Y-m-d', $row['starting_date_time']),
        );
    }

    public function list(int $currentPage = 1, int $pageSize = 25): SessionPage
    {
        $sql = <<<'SQL'
            SELECT id, born_id, payment_id, starting_date_time
            FROM charging_session
            LIMIT :limit
            OFFSET :offset
            SQL;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':limit', $pageSize);
        $statement->bindValue(':offset', ($currentPage - 1) * $pageSize);

        $result = $statement->executeQuery();

        if ($result->rowCount()<= 0) {
            throw new NotFoundException();
        }

        return new SessionPage(
            $currentPage,
            $pageSize,
            $result->rowCount(),
            ...Collection::fromTraversable($result->iterateAssociative())
                ->map(fn (array $row) => new Session(
                    SessionId::fromString($row['id']),
                    BornId::fromString($row['born_id']),
                    PaymentId::fromString($row['payment_id']),
                    \DateTimeImmutable::createFromFormat('Y-m-d', $row['starting_date_time']),
                ))
        );
    }
}
