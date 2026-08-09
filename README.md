# Zana DDD

Domain-Driven Design building blocks for Zana applications.

## Purpose

`mj-zana/ddd` contains the shared DDD base types used across application modules:

- `Zana\Ddd\Application\Command` — CQRS command base class
- `Zana\Ddd\Application\Query` — CQRS query base class
- `Zana\Ddd\Domain\Entity` — entity with identity
- `Zana\Ddd\Domain\AggregateRoot` — aggregate root with versioning + domain events
- `Zana\Ddd\Domain\ValueObject` — immutable value object
- `Zana\Ddd\Domain\DomainEvent` — domain event base class

These are framework-level abstractions. They have no external dependencies, so
the package can be installed independently.

## Install

```bash
composer require mj-zana/ddd
```

## Usage

```php
use Zana\Ddd\Domain\AggregateRoot;
use Zana\Ddd\Application\Command;

final class CreateUser extends Command
{
    public function __construct(public readonly string $name) { parent::__construct(); }
}

final class User extends AggregateRoot
{
    public function rename(string $name): void { /* ... */ }
}
```

