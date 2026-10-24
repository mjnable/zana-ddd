# Zana DDD

Domain-Driven Design and CQRS building blocks for Domain and Application code.
No framework dependencies. Requires PHP 8.5 or later.

```
composer require mj-zana/ddd
```

## Building blocks

| Type | Use |
|---|---|
| `Entity`, `AggregateRoot` | identity-based equality, recorded domain events (`recordEvent()`, `releaseEvents()`), version for optimistic concurrency |
| `ValueObject` | immutable (`readonly`), compared by value; nested value objects and identities compare by value too |
| `Identity` | typed ids: `PatientId::generate()`, `PatientId::fromString($s)` |
| `Uuid` | `Uuid::v7()` (time-ordered, strictly increasing within a process), `Uuid::isValid()` |
| `DomainEvent` | event id (UUID v7), occurrence time |
| `Result`, `DomainError` | `Result::ok($v)` / `Result::fail($error)`, with `map()` and `then()` |
| `Specification` | `isSatisfiedBy()`, composed with `and()`, `or()`, `not()` |
| `RepositoryInterface`, `UnitOfWorkInterface` | persistence contracts implemented by infrastructure (e.g. `mj-zana/database`) |
| `Command`, `Query` | CQRS messages with an id and a creation time |

```php
final readonly class PatientId extends Identity {}

final readonly class Height extends ValueObject
{
    public function __construct(public float $centimetres) {}
}

final class Patient extends AggregateRoot
{
    public static function register(PatientId $id, string $name): self
    {
        $patient = new self($id);
        $patient->recordEvent(new PatientRegistered($name));

        return $patient;
    }
}
```

## Upgrading from 1.x

- `ValueObject` is `readonly`, so subclasses must be declared `readonly` too. `with()` is removed.
- Event, command and query ids are UUID v7 strings instead of `uniqid()` values.
- `DomainEvent` takes an optional `occurredAt` as its second constructor argument.

## Development

```
composer install
composer test
composer analyse
```

## License

MIT — see [LICENSE](LICENSE).
