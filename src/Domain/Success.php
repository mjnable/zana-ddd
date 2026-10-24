<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * A successful Result. Create it with Result::ok().
 *
 * @template-covariant T
 * @extends Result<T>
 */
final readonly class Success extends Result
{
    /**
     * @param T $value
     */
    public function __construct(private mixed $value)
    {
    }

    public function isOk(): bool
    {
        return true;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function error(): DomainError
    {
        throw new DomainException('A successful result has no error.');
    }

    public function map(callable $transform): Result
    {
        return new Success($transform($this->value));
    }

    public function then(callable $next): Result
    {
        return $next($this->value);
    }
}
