<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for domain events: immutable, past-tense facts about the domain.
 */
abstract class DomainEvent
{
    protected readonly string $eventId;

    protected readonly \DateTimeImmutable $occurredAt;

    public function __construct(?string $eventId = null, ?\DateTimeImmutable $occurredAt = null)
    {
        $this->eventId = $eventId ?? Uuid::v7();
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getOccurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function getEventName(): string
    {
        return static::class;
    }
}
