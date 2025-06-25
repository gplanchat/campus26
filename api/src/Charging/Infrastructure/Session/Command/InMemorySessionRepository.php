<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Session\Command;

use App\Charging\Domain\ConflictException;
use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Session\Command\Session;
use App\Charging\Domain\Session\Command\SessionInitializedEvent;
use App\Charging\Domain\Session\Command\SessionRepositoryInterface;
use App\Charging\Domain\Session\Query\Session as QuerySession;
use App\Charging\Domain\Session\SessionId;
use App\Charging\Infrastructure\Session\DataFixtures\SessionDataFixtures;
use App\Charging\Infrastructure\StorageMock;
use Symfony\Contracts\Cache\ItemInterface;

final class InMemorySessionRepository implements SessionRepositoryInterface
{
    public function __construct(
        private StorageMock $storage,
    ) {
    }

    public function get(SessionId $sessionId): Session
    {
        $item = $this->storage->getItem(SessionDataFixtures::getCacheKey($sessionId));

        if (!$item->isHit()) {
            throw new NotFoundException();
        }

        $value = $item->get();
        if (!$value instanceof QuerySession) {
            throw new NotFoundException();
        }

        return new Session(
            $value->id,
        );
    }

    public function save(Session $session): void
    {
        foreach ($events = $session->releaseEvents() as $event) {
            try {
                $this->saveEvent($event);
            } catch (\Throwable $exception) {
                throw $exception;
            }
        }

//        foreach ($events as $event) {
//            $this->eventBus->emit($event);
//        }
    }

    private function saveEvent(object $event): void
    {
        $methodName = 'apply'.substr($event::class, strrpos($event::class, '\\') + 1);
        if (method_exists($this, $methodName)) {
            $this->{$methodName}($event);
        }
    }

    private function applySessionInitializedEvent(SessionInitializedEvent $event)
    {
        $this->storage->get(SessionDataFixtures::getCacheKey($event->id), function (ItemInterface $item) use ($event): QuerySession {
            if ($item->isHit()) {
                throw new ConflictException();
            }

            $item->tag(['session']);

            return new QuerySession(
                $event->id,
                $event->bornId,
                $event->paymentId,
                $event->startingDateTime,
            );
        });
    }
}
