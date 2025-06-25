<?php

declare(strict_types=1);

namespace App\Charging\Domain\Payment\Query;

use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Charging\Domain\Payment\PaymentId;
use Symfony\Component\Routing\Requirement\Requirement;

#[Get(
    uriTemplate: '/charging/payment/{id}',
    uriVariables: [
        'id',
    ],
    openapi: new Operation(
        parameters: [
            new Parameter(
                name: 'id',
                in: 'path',
                description: 'Identifier of the Payment',
                required: true,
                schema: ['pattern' => Requirement::UUID_V7],
            ),
        ],
    ),
    provider: QueryOnePaymentProvider::class
)]
final readonly class Payment
{
    public function __construct(
        public PaymentId $id,
    ) {}
}
