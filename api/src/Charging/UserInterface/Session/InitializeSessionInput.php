<?php

declare(strict_types=1);

namespace App\Charging\UserInterface\Session;

use ApiPlatform\Metadata\ApiProperty;
use App\Charging\Domain\Born\BornId;
use App\Charging\Domain\Payment\PaymentId;
use Symfony\Component\Serializer\Attribute\Context;

final readonly class InitializeSessionInput
{
    public function __construct(
        #[ApiProperty()]
        #[Context(['iri_only' => true])]
        public BornId $bornId,
        #[ApiProperty()]
        #[Context(['iri_only' => true])]
        public PaymentId $paymentId,
    ) {}
}
