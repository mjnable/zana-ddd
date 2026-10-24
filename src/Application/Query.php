<?php

declare(strict_types=1);

namespace Zana\Ddd\Application;

use Zana\Ddd\Domain\Uuid;

/**
 * Base class for CQRS queries: a request for data that changes nothing.
 */
abstract class Query
{
    protected readonly string $queryId;

    protected readonly \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->queryId = Uuid::v7();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getQueryId(): string
    {
        return $this->queryId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
