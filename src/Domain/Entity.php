<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Base class for domain entities.
 *
 * Entities have an identity that persists across changes: two entities of the
 * same class with the same id are equal, whatever their other state.
 */
abstract class Entity
{
    /** @var list<DomainEvent> */
    private array $domainEvents = [];

    public function __construct(protected mixed $id = null)
    {
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * Equal when both have the same class and id. Without an id on either
     * side, only the same instance is equal.
     */
    public function equals(self $other): bool
    {
        if ($this->id === null || $other->id === null) {
            return $this === $other;
        }

        if (static::class !== $other::class) {
            return false;
        }

        if ($this->id instanceof Identity && $other->id instanceof Identity) {
            return $this->id->equals($other->id);
        }

        return $this->id === $other->id;
    }

    /**
     * Return and clear the domain events recorded since the last call.
     *
     * @return list<DomainEvent>
     */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }

    public function clearEvents(): void
    {
        $this->domainEvents = [];
    }

    protected function recordEvent(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }
}
