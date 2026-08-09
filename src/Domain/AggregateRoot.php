<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for Aggregate Roots.
 * 
 * An Aggregate Root is the entry point for a cluster of domain objects
 * that must be treated as a single unit for data changes.
 * 
 * External code interacts with the aggregate through the root,
 * ensuring business invariants are maintained.
 */
abstract class AggregateRoot extends Entity
{
    /**
     * The version number for optimistic concurrency control.
     */
    protected int $version = 0;

    /**
     * Get the aggregate root's version number.
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Increment the version (called when changes are persisted).
     */
    public function incrementVersion(): void
    {
        $this->version++;
    }
}

