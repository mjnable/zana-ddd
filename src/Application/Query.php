<?php

declare(strict_types=1);

namespace Zana\Ddd\Application;

/**
 * Base class for CQRS Queries.
 * 
 * Queries retrieve data without modifying state.
 * They never publish domain events or start transactions.
 * Multiple queries can share the same read model.
 */
abstract class Query
{
    /**
     * The unique query identifier for tracing.
     */
    protected string $queryId;

    /**
     * When the query was created.
     */
    protected \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->queryId = uniqid('qry-', true);
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get the query identifier.
     *
     * @return string
     */
    public function getQueryId(): string
    {
        return $this->queryId;
    }

    /**
     * Get when the query was created.
     *
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

