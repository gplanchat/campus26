<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Query;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use App\Charging\Domain\Session\SessionId;
use App\Charging\UserInterface\Session\QuerySeveralSessionProvider;
use Symfony\Component\Serializer\Attribute\Context;

#[GetCollection(
    uriTemplate: '/charging/session',
    openapi: new Operation(
        parameters: [],
    ),
    shortName: 'ChargingSession',
    paginationEnabled: true,
    paginationItemsPerPage: 25,
    paginationMaximumItemsPerPage: 100,
    paginationPartial: true,
    order: ['id' => 'ASC'],
    provider: QuerySeveralSessionProvider::class,
    itemUriTemplate: '/charging/session/{id}',
)]
final readonly class Session
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public SessionId $id,
    ) {}
}
