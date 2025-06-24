<?php

declare(strict_types=1);

namespace App\Platform\Collection;

/**
 * @template Key of array-key
 * @template Type
 *
 * @implements \IteratorAggregate<Key, Type>
 */
final readonly class EmptyCollection implements \IteratorAggregate, CollectionInterface
{
    /**
     * @return array<Key, Type>
     */
    public function toArray(): array
    {
        return [];
    }

    public function getIterator(): \Traversable
    {
        return new \EmptyIterator();
    }

    /**
     * @param callable(Type): bool $filter
     *
     * @return self<Key, Type>
     */
    public function filter(callable $filter): self
    {
        return $this;
    }

    /**
     * @template ReturnType
     *
     * @param callable(Type): ReturnType $map
     *
     * @return self<Key, ReturnType>
     */
    public function map(callable $map): self
    {
        return $this;
    }

    /**
     * @param callable(Type $left, Type $right): bool $callable
     *
     * @return self<Key, Type>
     */
    public function unique(callable $callable): self
    {
        return $this;
    }

    /**
     * @template ReturnType of mixed
     *
     * @param callable(ReturnType $carry, Type $item): ReturnType $reducer
     * @param ReturnType                                          $initial
     *
     * @return ReturnType
     */
    public function reduce(callable $reducer, mixed $initial): mixed
    {
        return $initial;
    }

    /**
     * @return self<Key, Type>
     */
    public function offset(int $offset): self
    {
        return $this;
    }

    /**
     * @return self<Key, Type>
     */
    public function limit(int $limit): self
    {
        return $this;
    }

    /**
     * @param callable(Type): bool $callable
     */
    public function any(callable $callable): bool
    {
        return false;
    }

    /**
     * @param callable(Type): bool $callable
     */
    public function none(callable $callable): bool
    {
        return true;
    }

    /**
     * @param callable(Type): bool $callable
     */
    public function all(callable $callable): bool
    {
        return true;
    }
}
