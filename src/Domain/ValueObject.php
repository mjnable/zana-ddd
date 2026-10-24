<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for value objects: immutable, without identity, compared by value.
 *
 * The class is readonly, so every subclass must be declared `readonly` too.
 * Equality compares the class and getEqualityValues(), which defaults to every
 * property.
 */
abstract readonly class ValueObject
{
    public function equals(self $other): bool
    {
        return static::class === $other::class
            && self::same($this->getEqualityValues(), $other->getEqualityValues());
    }

    /**
     * Values compared by equals(). Override when some properties must not count.
     *
     * @return array<string, mixed>
     */
    protected function getEqualityValues(): array
    {
        return get_object_vars($this);
    }

    /**
     * Strict comparison, except that nested value objects and identities use
     * their own equals() and other objects compare by state.
     */
    private static function same(mixed $a, mixed $b): bool
    {
        if ($a instanceof self && $b instanceof self) {
            return $a->equals($b);
        }

        if ($a instanceof Identity && $b instanceof Identity) {
            return $a->equals($b);
        }

        if (is_array($a) && is_array($b)) {
            if (array_keys($a) !== array_keys($b)) {
                return false;
            }
            foreach ($a as $key => $value) {
                if (!self::same($value, $b[$key])) {
                    return false;
                }
            }

            return true;
        }

        if (is_object($a) && is_object($b)) {
            return $a::class === $b::class && $a == $b;
        }

        return $a === $b;
    }
}
