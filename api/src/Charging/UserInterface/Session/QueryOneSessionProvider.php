<?php

declare(strict_types=1);

namespace App\Charging\UserInterface\Session;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Session\Query\Session;
use App\Charging\Domain\Session\Query\SessionRepositoryInterface;
use App\Charging\Domain\Session\Query\UseCases\GetOneSessionQuery;
use App\Charging\Domain\Session\SessionId;
use App\Platform\QueryBusInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class QueryOneSessionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param array{id: non-empty-string} $uriVariables
     *
     * @return Session
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Session
    {
        try {
            $query = new GetOneSessionQuery(SessionId::fromString($uriVariables[ 'id' ]));
            return $this->queryBus->query($query);
        } catch (NotFoundException $exception) {
            throw new NotFoundHttpException(previous: $exception);
        }
    }
}
