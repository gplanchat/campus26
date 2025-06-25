<?php

declare(strict_types=1);

namespace App\Charging\Domain\Born;

use App\Charging\InvalidUriFormatException;
use Assert\Assertion;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

final readonly class BornId implements \Stringable
{
    public const string PARSE = '/^\/charging\/born\/(?<reference>'.Requirement::UUID_V7.')$/';

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

    public static function fromUri(string $uri): self
    {
        if (!preg_match(self::PARSE, $uri, $matches)) {
            throw new InvalidUriFormatException(\sprintf('<%s> is not a valid URI.', $uri));
        }

        return new self($matches['reference']);
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
