<?php

declare(strict_types=1);

namespace App\Charging\UserInterface\Session;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\PaginatorInterface;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Charging\Domain\Session\Query\SessionRepositoryInterface;

final readonly class QuerySeveralSessionProvider implements ProviderInterface
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
        private Pagination $pagination,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): PaginatorInterface
    {
        $sessionPage = $this->sessionRepository->list(
            $this->pagination->getPage($context),
            $this->pagination->getLimit($operation, $context),
        );

        return new TraversablePaginator($sessionPage, $sessionPage->currentPage, $sessionPage->pageSize, $sessionPage->totalItems);
    }
}
