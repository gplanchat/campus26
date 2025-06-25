<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Session\Command;

use App\Charging\Domain\ConflictException;
use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Session\Command\Session;
use App\Charging\Domain\Session\Command\SessionInitializedEvent;
use App\Charging\Domain\Session\Command\SessionRepositoryInterface;
use App\Charging\Domain\Session\SessionId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class DatabaseSessionRepository implements SessionRepositoryInterface
{
    public function __construct(
        #[Autowire('@db.connection')]
        private Connection $connection,
//        private EventBusInterface $eventBus,
    ) {
    }

    public function get(SessionId $sessionId): Session
    {
        $sql = <<<'SQL'
            SELECT id, version
            FROM charging_session
            WHERE id = :id
            LIMIT 1
            SQL;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':id', $sessionId);

        $result = $statement->executeQuery();

        if ($result->rowCount()<= 0) {
            throw new NotFoundException();
        }

        $row = $result->fetchAssociative();

        return new Session(
            SessionId::fromString($row['id']),
            (int) $row['version'],
        );
    }

    public function save(Session $session): void
    {
        foreach ($session->releaseEvents() as $event) {
            $this->saveEvent($event);
        }
    }

    private function saveEvent(object $event): void
    {
        $methodName = 'apply'.substr($event::class, strrpos($event::class, '\\') + 1);
        if (method_exists($this, $methodName)) {
            $this->{$methodName}($event);
        }
    }

    private function applySessionInitializedEvent(SessionInitializedEvent $event): void
    {
        $sql = <<<'SQL'
            INSERT INTO charging_session (id, born_id, payment_id, starting_date_time, version)
            VALUES (:id, :born_id, :payment_id, :starting_date_time, 1)
            SQL;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':id', $event->id);
        $statement->bindValue(':born_id', $event->bornId->toString());
        $statement->bindValue(':payment_id', $event->paymentId->toString());
        $statement->bindValue(':starting_date_time', $event->startingDateTime->format('Y-m-d'));

        try {
            $result = $statement->executeQuery();
        } catch (ConstraintViolationException $exception) {
            throw new ConflictException(previous: $exception);
        }

        if ($result->rowCount() <= 0) {
            throw new \RuntimeException();
        }
    }
}
