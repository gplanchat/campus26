<?php

declare(strict_types=1);

namespace App\Platform\Collection;

/**
 * @template Type
 * @template ReturnType
 *
 * @implements \Iterator<mixed, ReturnType>
 */
final class BufferIterator implements \Iterator
{
    private int $key = 0;
    private bool $finished = false;
    private array $buffer = [];

    /**
     * @param \Iterator<mixed, Type> $decorated
     */
    public function __construct(
        private readonly \Iterator $decorated,
        private readonly int $size,
    ) {
    }

    public function current(): mixed
    {
        return $this->buffer;
    }

    public function next(): void
    {
        $this->consume();
        ++$this->key;
    }

    public function key(): mixed
    {
        return $this->key;
    }

    public function valid(): bool
    {
        return !$this->finished;
    }

    public function rewind(): void
    {
        $this->decorated->rewind();

        $this->consume();
        $this->key = 0;
    }

    private function consume(): void
    {
        if (!$this->decorated->valid()) {
            $this->finished = true;

            return;
        }

        $this->buffer = [];

        for ($read = 0; $read < $this->size; ++$read) {
            if (!$this->decorated->valid()) {
                return;
            }

            $this->buffer[] = $this->decorated->current();

            $this->decorated->next();
        }
    }
}
