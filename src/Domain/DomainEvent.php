<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for Domain Events.
 * 
 * Domain events represent facts that have happened in the business domain.
 * They are immutable, past-tense, and carry the data relevant to the event.
 * 
 * Events are used to communicate between aggregates, modules, or bounded contexts
 * without creating coupling.
 */
abstract class DomainEvent
{
    /**
     * The unique identifier for this event occurrence.
     */
    protected string $eventId;

    /**
     * When the event occurred.
     */
    protected \DateTimeImmutable $occurredAt;

    /**
     * @param string|null $eventId Optional event identifier (auto-generated if null)
     */
    public function __construct(?string $eventId = null)
    {
        $this->eventId = $eventId ?? uniqid('evt-', true);
        $this->occurredAt = new \DateTimeImmutable();
    }

    /**
     * Get the event identifier.
     *
     * @return string
     */
    public function getEventId(): string
    {
        return $this->eventId;
    }

    /**
     * Get the date and time when the event occurred.
     *
     * @return \DateTimeImmutable
     */
    public function getOccurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    /**
     * Get the event name (class name by default).
     *
     * @return string
     */
    public function getEventName(): string
    {
        return static::class;
    }
}

