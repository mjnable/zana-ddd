<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for typed identifiers, such as PatientId.
 *
 *     final readonly class PatientId extends Identity {}
 *     $id = PatientId::generate();
 *     $same = PatientId::fromString((string) $id);
 */
abstract readonly class Identity implements \Stringable
{
    final protected function __construct(public string $value)
    {
    }

    public static function generate(): static
    {
        return new static(Uuid::v7());
    }

    /**
     * @throws DomainException If the value is not a valid identifier
     */
    public static function fromString(string $value): static
    {
        if (!static::isValidValue($value)) {
            throw new DomainException(sprintf('"%s" is not a valid %s.', $value, static::class));
        }

        return new static(strtolower($value));
    }

    /**
     * Override to accept identifiers that are not UUIDs.
     */
    public static function isValidValue(string $value): bool
    {
        return Uuid::isValid($value);
    }

    public function equals(self $other): bool
    {
        return static::class === $other::class && $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
