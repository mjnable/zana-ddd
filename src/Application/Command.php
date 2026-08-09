<?php

declare(strict_types=1);

namespace Zana\Ddd\Application;

/**
 * Base class for CQRS Commands.
 * 
 * Commands represent business actions that modify state.
 * They express intent (what should happen), not implementation (how it happens).
 * 
 * Each command has exactly one handler.
 */
abstract class Command
{
    /**
     * The unique command identifier for tracing.
     */
    protected string $commandId;

    /**
     * When the command was created.
     */
    protected \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->commandId = uniqid('cmd-', true);
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get the command identifier.
     *
     * @return string
     */
    public function getCommandId(): string
    {
        return $this->commandId;
    }

    /**
     * Get when the command was created.
     *
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

