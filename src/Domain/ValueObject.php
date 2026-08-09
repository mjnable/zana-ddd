<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for Value Objects.
 * 
 * Value Objects are immutable, have no identity, and are compared by value.
 * Equality is based on all properties being equal.
 */
abstract class ValueObject
{
    /**
     * Compare two Value Objects for equality.
     *
     * @param self $other
     * @return bool
     */
    public function equals(self $other): bool
    {
        return get_class($this) === get_class($other)
            && $this->getEqualityValues() === $other->getEqualityValues();
    }

    /**
     * Get the values used for equality comparison.
     * Override in child classes when the default property comparison is not appropriate.
     *
     * @return array<string, mixed>
     */
    protected function getEqualityValues(): array
    {
        return get_object_vars($this);
    }

    /**
     * Create a new instance with modified properties.
     * Maintains immutability by returning a new instance.
     *
     * @param array<string, mixed> $data Properties to change
     * @return static
     */
    public function with(array $data): static
    {
        $clone = clone $this;
        foreach ($data as $property => $value) {
            $clone->{$property} = $value;
        }
        return $clone;
    }

    /**
     * Prevent cloning from modifying the original.
     */
    protected function __clone()
    {
    }
}

