<?php

declare(strict_types=1);

namespace App\Platform\Collection;

/**
 * @template Key of array-key
 * @template Type
 *
 * @extends \Traversable<Key, Type>
 */
interface CollectionInterface extends \Traversable
{
    /**
     * @return array<Key, Type>
     */
    public function toArray(): array;

    /**
     * @param callable(Type): bool $filter
     *
     * @return Collection<Type>
     */
    public function filter(callable $filter): self;

    /**
     * @template ReturnType
     *
     * @param callable(Type): ReturnType $map
     *
     * @return Collection<ReturnType>
     */
    public function map(callable $map): self;

    /**
     * @template ReturnType of mixed
     *
     * @param callable(ReturnType $carry, Type $item): ReturnType $reducer
     * @param ReturnType                                          $initial
     *
     * @return ReturnType
     */
    public function reduce(callable $reducer, mixed $initial): mixed;

    /**
     * @param callable(Type $left, Type $right): bool $callable
     *
     * @return Collection<Type>
     */
    public function unique(callable $callable): self;

    /**
     * @return Collection<Type>
     */
    public function offset(int $offset): self;

    /**
     * @return Collection<Type>
     */
    public function limit(int $limit): self;

    /**
     * @param callable(Type): bool $callable
     */
    public function any(callable $callable): bool;

    /**
     * @param callable(Type): bool $callable
     */
    public function none(callable $callable): bool;

    /**
     * @param callable(Type): bool $callable
     */
    public function all(callable $callable): bool;
}
