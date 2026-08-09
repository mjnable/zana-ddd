<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for Domain Entities.
 * 
 * Entities have identity that persists across changes.
 * Two entities with the same identity are considered equal,
 * even if their properties differ.
 */
abstract class Entity
{
    /**
     * The unique identifier of this entity.
     *
     * @var mixed
     */
    protected mixed $id;

    /**
     * @var array<int, DomainEvent> Events raised by this entity
     */
    private array $domainEvents = [];

    /**
     * @param mixed $id The unique identifier
     */
    public function __construct(mixed $id = null)
    {
        $this->id = $id;
    }

    /**
     * Get the entity's identifier.
     *
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * Entities are compared by identity.
     */
    public function equals(self $other): bool
    {
        if ($this->id === null || $other->id === null) {
            return $this === $other;
        }

        return static::class === $other::class && $this->id === $other->id;
    }

    /**
     * Record a domain event.
     */
    protected function recordEvent(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * Get and clear all recorded domain events.
     *
     * @return array<int, DomainEvent>
     */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }

    /**
     * Clear all recorded domain events without releasing them.
     */
    public function clearEvents(): void
    {
        $this->domainEvents = [];
    }
}

