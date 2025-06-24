<?php

declare(strict_types=1);

namespace App\Platform\Collection;

/**
 * @template Type
 * @template ReturnType
 *
 * @implements \Iterator<mixed, ReturnType>
 */
final readonly class MapIterator implements \Iterator
{
    /**
     * @param \Iterator<mixed, Type>                        $decorated
     * @param \Closure(Type $current, int $key): ReturnType $map
     */
    public function __construct(
        private \Iterator $decorated,
        private \Closure $map,
    ) {
    }

    public function current(): mixed
    {
        return ($this->map)($this->decorated->current(), $this->decorated->key());
    }

    public function next(): void
    {
        $this->decorated->next();
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
        $this->decorated->rewind();
    }
}
