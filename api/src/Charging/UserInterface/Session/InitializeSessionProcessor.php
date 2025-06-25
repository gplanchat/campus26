<?php

declare(strict_types=1);

namespace App\Charging\UserInterface\Session;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Charging\Domain\ConflictException;
use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Session\Command\Session as CommandSession;
use App\Charging\Domain\Session\Command\SessionRepositoryInterface as CommandSessionRepositoryInterface;
use App\Charging\Domain\Session\Query\SessionRepositoryInterface as QuerySessionRepositoryInterface;
use App\Charging\Domain\Session\Query\Session as QuerySession;
use App\Charging\Domain\Session\SessionId;
use Psr\Clock\ClockInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class InitializeSessionProcessor implements ProcessorInterface
{
    public function __construct(
        private ClockInterface $clock,
        private CommandSessionRepositoryInterface $commandSessionRepository,
        private QuerySessionRepositoryInterface $querySessionRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): QuerySession
    {
        if (!$data instanceof InitializeSessionInput) {
            throw new BadRequestHttpException();
        }

        $session = CommandSession::initializeSession(
            $sessionId = SessionId::createRandom(),
            $data->paymentId,
            $data->bornId,
            $this->clock->now(),
        );

        try {
            $this->commandSessionRepository->save($session);
        } catch (ConflictException $exception) {
            throw new ConflictHttpException(previous: $exception);
        }

        try {
            return $this->querySessionRepository->get($sessionId);
        } catch (NotFoundException $exception) {
            throw new NotFoundHttpException(previous: $exception);
        }
    }
}
