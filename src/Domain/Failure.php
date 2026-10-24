<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * A failed Result. Create it with Result::fail().
 *
 * @extends Result<never>
 */
final readonly class Failure extends Result
{
    public function __construct(private DomainError $error)
    {
    }

    public function isOk(): bool
    {
        return false;
    }

    public function value(): never
    {
        throw new DomainException("Cannot read the value of a failed result: {$this->error->message}");
    }

    public function error(): DomainError
    {
        return $this->error;
    }

    public function map(callable $transform): Result
    {
        return $this;
    }

    public function then(callable $next): Result
    {
        return $this;
    }
}
