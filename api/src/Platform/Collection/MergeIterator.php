<?php

declare(strict_types=1);

namespace App\Platform\Collection;

/**
 * @template Type
 * @template ReturnType
 *
 * @implements \Iterator<mixed, ReturnType>
 */
final class MergeIterator implements \Iterator
{
    private int $key = 0;
    private \Iterator $buffer;

    /**
     * @param \Iterator<mixed, Type> $decorated
     */
    public function __construct(
        private \Iterator $decorated,
    ) {
        $this->buffer = new \AppendIterator();
    }

    public function current(): mixed
    {
        return $this->buffer->current();
    }

    public function next(): void
    {
        $this->buffer->next();
        $this->consume();
        ++$this->key;
    }

    public function key(): mixed
    {
        return $this->key;
    }

    public function valid(): bool
    {
        return $this->buffer->valid();
    }

    public function rewind(): void
    {
        $this->decorated->rewind();
        $this->buffer = new \AppendIterator();

        $this->consume();
        $this->key = 0;
    }

    private function consume(): void
    {
        if (!$this->buffer->valid() && $this->decorated->valid()) {
            $current = $this->decorated->current();
            if (\is_array($current)) {
                $this->buffer->append(new \ArrayIterator($current));
            } elseif ($current instanceof \Iterator) {
                $this->buffer->append($current);
            } else {
                $this->buffer->append(new \IteratorIterator($current));
            }
            $this->decorated->next();
        }
    }
}
