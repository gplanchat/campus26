<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Query;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Charging\Domain\Born\BornId;
use App\Charging\Domain\Payment\PaymentId;
use App\Charging\Domain\Session\SessionId;
use App\Charging\UserInterface\Session\InitializeSessionInput;
use App\Charging\UserInterface\Session\InitializeSessionProcessor;
use App\Charging\UserInterface\Session\QueryOneSessionProvider;
use App\Charging\UserInterface\Session\QuerySeveralSessionProvider;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Attribute\Context;

#[GetCollection(
    uriTemplate: '/charging/session',
    openapi: new Operation(
        parameters: [],
    ),
    paginationEnabled: true,
    paginationItemsPerPage: 25,
    paginationMaximumItemsPerPage: 100,
    paginationPartial: true,
    order: ['id' => 'ASC'],
    provider: QuerySeveralSessionProvider::class,
    itemUriTemplate: '/charging/session/{id}',
)]
#[Get(
    uriTemplate: '/charging/session/{id}',
    uriVariables: [
        'id',
    ],
    openapi: new Operation(
        parameters: [
            new Parameter(
                name: 'id',
                in: 'path',
                description: 'Identifier of the Session',
                required: true,
                schema: ['pattern' => Requirement::UUID_V7],
            ),
        ],
    ),
    provider: QueryOneSessionProvider::class
)]
#[Post(
    uriTemplate: '/charging/session',
    openapi: new Operation(
        summary: 'Initializes the session process',
    ),
    description: 'Initializes the session process',
    input: InitializeSessionInput::class,
    output: self::class,
    processor: InitializeSessionProcessor::class,
    itemUriTemplate: '/charging/session/{uuid}',
)]
final readonly class Session
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public SessionId $id,
        #[ApiProperty()]
        #[Context(['iri_only' => true])]
        public BornId $bornId,
        #[ApiProperty()]
        #[Context(['iri_only' => true])]
        public PaymentId $paymentId,
        #[ApiProperty(
            description: 'Starting date of creation of the Session',
            schema: ['type' => 'string', 'format' => 'date', 'required' => true, 'nullable' => false],
        )]
        #[Context(['datetime_format' => \DateTimeInterface::W3C, 'skip_null_values' => false])]
        public \DateTimeInterface $startingDateTime,
    ) {}
}
