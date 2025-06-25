<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Command;

use App\Charging\Domain\Born\BornId;
use App\Charging\Domain\Payment\PaymentId;
use App\Charging\Domain\Session\SessionId;

final class Session
{
    /** @internal should only be used inside tests dans this class */
    public function __construct(
        private SessionId $id,
        private int $version = 0,
        private array $events = [],
    ) {}

    private function apply(object $event): void
    {
        $methodName = 'apply'.substr($event::class, strrpos($event::class, '\\') + 1);
        if (method_exists($this, $methodName)) {
            $this->{$methodName}($event);
        }
    }

    private function recordThat(object $event): void
    {
        $this->events[] = $event;
        ++$this->version;
        $this->apply($event);
    }

    /**
     * @return object[]
     */
    public function releaseEvents(): array
    {
        $releasedEvents = $this->events;
        $this->events = [];

        return $releasedEvents;
    }

    public static function initializeSession(
        SessionId $id,
        PaymentId $paymentId,
        BornId $bornId,
        \DateTimeInterface $startingDateTime,
    ): self {
        $instance = new self($id);

        $instance->recordThat(new SessionInitializedEvent(
            $id,
            1,
            $paymentId,
            $bornId,
            $startingDateTime,
        ));

        return $instance;
    }

    private function applySessionInitializedEvent(SessionInitializedEvent $event): void
    {
    }
}
