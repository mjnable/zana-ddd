<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * The outcome of an operation that can fail for a domain reason: a Success
 * carrying a value, or a Failure carrying a DomainError.
 *
 * @template-covariant T
 */
abstract readonly class Result
{
    /**
     * @template V
     * @param V $value
     * @return Success<V>
     */
    public static function ok(mixed $value = null): Success
    {
        return new Success($value);
    }

    /**
     * A failure. A string becomes a DomainError with the code "error".
     */
    public static function fail(DomainError|string $error): Failure
    {
        return new Failure(is_string($error) ? new DomainError('error', $error) : $error);
    }

    abstract public function isOk(): bool;

    public function isFail(): bool
    {
        return !$this->isOk();
    }

    /**
     * @return T
     * @throws DomainException On a failure
     */
    abstract public function value(): mixed;

    /**
     * @throws DomainException On a success
     */
    abstract public function error(): DomainError;

    /**
     * Transform the value of a success; a failure passes through unchanged.
     *
     * @template U
     * @param callable(T): U $transform
     * @return Result<U>
     */
    abstract public function map(callable $transform): Result;

    /**
     * Chain an operation that returns a Result; a failure passes through unchanged.
     *
     * @template U
     * @param callable(T): Result<U> $next
     * @return Result<U>
     */
    abstract public function then(callable $next): Result;
}
