<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Command;

use App\Charging\Domain\Born\BornId;
use App\Charging\Domain\Payment\PaymentId;
use App\Charging\Domain\Session\SessionId;

final readonly class SessionInitializedEvent
{
    public function __construct(
        public SessionId $id,
        public int $version,
        public PaymentId $paymentId,
        public BornId $bornId,
        public \DateTimeInterface $startingDateTime,
    ) {}
}
