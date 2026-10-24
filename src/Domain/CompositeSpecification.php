<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * A specification built by and(), or() or not().
 *
 * @internal
 */
final class CompositeSpecification extends Specification
{
    /**
     * @param \Closure(mixed): bool $rule
     */
    public function __construct(private readonly \Closure $rule)
    {
    }

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return ($this->rule)($candidate);
    }
}
