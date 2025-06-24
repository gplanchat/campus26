<?php

declare(strict_types=1);

namespace App\Platform\Collection;

/**
 * @template Key of array-key
 * @template Type
 *
 * @implements \IteratorAggregate<Key, Type>
 */
final readonly class Collection implements \IteratorAggregate, CollectionInterface
{
    /**
     * @param \Iterator<Key, Type> $items
     */
    private function __construct(
        private \Iterator $items,
    ) {
    }

    public static function pageRange(int $totalItems, int $currentPage = 1, int $pageSize = 25): self
    {
        if ($totalItems < ($currentPage - 1) * $pageSize) {
            return new self(new \EmptyIterator());
        }

        $start = max(0, ($currentPage - 1) * $pageSize);

        if (($totalItems - $start) < 0) {
            return new self(new \EmptyIterator());
        }

        return new self(
            new \ArrayIterator(range($start, min($pageSize, $totalItems - $start))),
        );
    }

    public static function range(int $size, int $start = 0, int $step = 1): self
    {
        return new self(
            new \ArrayIterator(range($start, $size, $step)),
        );
    }

    /**
     * @param Type ...$values
     *
     * @return self<Key, Type>
     */
    public static function with(...$values): self
    {
        return new self(new \ArrayIterator(array_values($values)));
    }

    /**
     * @param \Iterator<Key, Type> $data
     *
     * @return self<Key, Type>
     */
    public static function fromIterator(\Iterator $data): self
    {
        return new self($data);
    }

    /**
     * @param \Traversable<Key, Type> $data
     *
     * @return self<Key, Type>
     */
    public static function fromTraversable(\Traversable $data): self
    {
        return new self(new \IteratorIterator($data));
    }

    /**
     * @param array<Key, Type> $data
     *
     * @return self<Key, Type>
     */
    public static function fromArray(array $data): self
    {
        return new self(new \ArrayIterator($data));
    }

    /**
     * @return array<Key, Type>
     */
    public function toArray(): array
    {
        return iterator_to_array($this->items, false);
    }

    public function getIterator(): \Traversable
    {
        return $this->items;
    }

    /**
     * @param callable(Type): bool $filter
     *
     * @return self<Key, Type>
     */
    public function filter(callable $filter): self
    {
        return new self(
            new \CallbackFilterIterator($this->items, $filter),
        );
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
        return new self(
            new MapIterator($this->items, $map(...))
        );
    }

    public function buffer(int $count): self
    {
        return new self(
            new BufferIterator($this->items, $count),
        );
    }

    /**
     * @template ReturnType
     *
     * @param callable(iterable<array-key, Type> $buffer, int $currentPage, int $pageSize): iterable<array-key, ReturnType> $map
     *
     * @return self<Key, ReturnType>
     */
    public function pageMap(int $pageSize, callable $map): self
    {
        return new self(
            new MergeIterator(
                new MapIterator(
                    new BufferIterator($this->items, $pageSize),
                    fn (array $buffer, int $currentPage) => $map($buffer, $currentPage, $pageSize)
                ),
            ),
        );
    }

    /**
     * @return self<Key, Type>
     */
    public function merge(): self
    {
        return new self(
            new MergeIterator($this->items),
        );
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
        $carry = $initial;
        foreach ($this->items as $item) {
            $carry = $reducer($carry, $item);
        }

        return $carry;
    }

    /**
     * @return self<Key, Type>
     */
    public function degroup(): self
    {
        return new self(
            new MergeIterator($this->items)
        );
    }

    /**
     * @param callable(Type $left, Type $right): bool $callable
     *
     * @return self<Key, Type>
     */
    public function unique(callable $callable): self
    {
        $values = $this->toArray();
        $left = self::fromArray($values);
        $right = self::fromArray($values);

        $index = 0;

        return $left->filter(function ($current) use ($callable, $right, &$index) {
            try {
                $slice = $right->offset(++$index);

                return $slice->none(fn ($cloned) => $callable($current, $cloned));
            } catch (\OutOfBoundsException) {
                return true;
            }
        });
    }

    /**
     * @return self<Key, Type>
     */
    public function offset(int $offset): self
    {
        return new self(
            new \LimitIterator($this->items, $offset),
        );
    }

    /**
     * @return self<Key, Type>
     */
    public function limit(int $limit): self
    {
        return new self(
            new \LimitIterator($this->items, 0, $limit),
        );
    }

    /**
     * @param callable(Type): bool $callable
     */
    public function any(callable $callable): bool
    {
        return array_any($this->toArray(), $callable);
    }

    /**
     * @param callable(Type): bool $callable
     */
    public function none(callable $callable): bool
    {
        return array_all($this->toArray(), fn ($item) => !$callable($item));
    }

    /**
     * @param callable(Type): bool $callable
     */
    public function all(callable $callable): bool
    {
        return array_all($this->toArray(), $callable);
    }
}
