<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session\Query;

final readonly class SessionPage implements \IteratorAggregate, \Countable
{
    /** @var Session[] */
    public array $sessions;

    public function __construct(
        public int $currentPage,
        public int $pageSize,
        public int $totalItems,
        Session ...$sessions,
    ) {
        $this->sessions = $sessions;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->sessions);
    }

    public function count(): int
    {
        return count($this->sessions);
    }
}
