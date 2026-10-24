<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for aggregate roots: the entry point of a cluster of domain
 * objects that changes as one unit.
 *
 * The version supports optimistic concurrency: persistence increments it when
 * changes are saved.
 */
abstract class AggregateRoot extends Entity
{
    protected int $version = 0;

    public function getVersion(): int
    {
        return $this->version;
    }

    public function incrementVersion(): void
    {
        $this->version++;
    }
}
