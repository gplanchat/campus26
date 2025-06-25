<?php

declare(strict_types=1);

namespace App\Charging\Domain\Born\Query;

use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Charging\Domain\Born\BornId;
use Symfony\Component\Routing\Requirement\Requirement;

#[Get(
    uriTemplate: '/charging/born/{id}',
    uriVariables: [
        'id',
    ],
    openapi: new Operation(
        parameters: [
            new Parameter(
                name: 'id',
                in: 'path',
                description: 'Identifier of the Born',
                required: true,
                schema: ['pattern' => Requirement::UUID_V7],
            ),
        ],
    ),
    provider: QueryOneBornProvider::class
)]
final readonly class Born
{
    public function __construct(
        public BornId $id,
    ) {}
}
