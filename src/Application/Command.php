<?php

declare(strict_types=1);

namespace Zana\Ddd\Application;

use Zana\Ddd\Domain\Uuid;

/**
 * Base class for CQRS commands: an intent to change state, handled by exactly one handler.
 */
abstract class Command
{
    protected readonly string $commandId;

    protected readonly \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->commandId = Uuid::v7();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getCommandId(): string
    {
        return $this->commandId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
