<?php

declare(strict_types=1);

namespace App\Platform\Collection;

final class Cursor implements \Iterator
{
    private int $currentPage = 1;
    private \Iterator $decorated;

    /**
     * @param \Closure(int $page): \Traversable $nextPageCallback
     */
    public function __construct(
        private readonly \Closure $nextPageCallback,
    ) {
    }

    public function current(): mixed
    {
        return $this->decorated->current();
    }

    public function next(): void
    {
        if (!$this->decorated->valid()) {
            $this->fetchNextPage();
        }
    }

    public function key(): mixed
    {
        return $this->decorated->key();
    }

    public function valid(): bool
    {
        return $this->decorated->valid();
    }

    public function rewind(): void
    {
        $this->currentPage = 1;
        $this->fetchNextPage();

        $this->decorated->rewind();
    }

    private function fetchNextPage(): void
    {
        $iterator = ($this->nextPageCallback)($this->currentPage++);

        if ($iterator instanceof \Iterator) {
            $this->decorated = $iterator;
        } else {
            $this->decorated = new \IteratorIterator($iterator);
        }
    }
}
