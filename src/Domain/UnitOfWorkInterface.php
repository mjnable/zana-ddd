<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Groups changes so they are committed or rolled back together.
 */
interface UnitOfWorkInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;

    /**
     * Begin, run the callback and commit; on any exception roll back and rethrow.
     *
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function transactional(callable $callback): mixed;
}
