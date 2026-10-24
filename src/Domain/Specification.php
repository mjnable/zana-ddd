<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * A business rule that a candidate satisfies or not, composable with and(), or() and not().
 */
abstract class Specification
{
    abstract public function isSatisfiedBy(mixed $candidate): bool;

    public function and(self $other): self
    {
        return new CompositeSpecification(
            fn (mixed $candidate): bool => $this->isSatisfiedBy($candidate) && $other->isSatisfiedBy($candidate)
        );
    }

    public function or(self $other): self
    {
        return new CompositeSpecification(
            fn (mixed $candidate): bool => $this->isSatisfiedBy($candidate) || $other->isSatisfiedBy($candidate)
        );
    }

    public function not(): self
    {
        return new CompositeSpecification(fn (mixed $candidate): bool => !$this->isSatisfiedBy($candidate));
    }
}
