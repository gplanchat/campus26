<?php

declare(strict_types=1);

namespace App\Charging\Domain\Session;

use Assert\Assertion;
use Symfony\Component\Uid\Uuid;

final readonly class SessionId implements \Stringable
{
    private function __construct(
        private string $reference,
    ) {}

    public static function createRandom(): self
    {
        return new self(Uuid::v7()->toString());
    }

    public static function fromString(string $reference): self
    {
        Assertion::uuid($reference);

        return new self($reference);
    }

    public function toString(): string
    {
        return $this->reference;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(self $other): bool
    {
        return strcmp($this->reference, $other->reference) === 0;
    }
}
