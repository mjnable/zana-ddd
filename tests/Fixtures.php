<?php

declare(strict_types=1);

namespace Zana\Ddd\Tests;

use Zana\Ddd\Application\Command;
use Zana\Ddd\Application\Query;
use Zana\Ddd\Domain\AggregateRoot;
use Zana\Ddd\Domain\DomainEvent;
use Zana\Ddd\Domain\Identity;
use Zana\Ddd\Domain\Specification;
use Zana\Ddd\Domain\ValueObject;

final readonly class PatientId extends Identity
{
}

final readonly class Ticket extends Identity
{
    public static function isValidValue(string $value): bool
    {
        return preg_match('/^T-\d+$/', $value) === 1;
    }
}

final readonly class Height extends ValueObject
{
    public function __construct(public float $centimetres, public string $unit = 'cm')
    {
    }
}

final readonly class Measurement extends ValueObject
{
    /**
     * @param list<int> $tags
     */
    public function __construct(public Height $height, public PatientId $patient, public array $tags = [], public ?\DateTimeImmutable $at = null)
    {
    }
}

final readonly class Money extends ValueObject
{
    public function __construct(public int $cents, public string $currency, public string $label = '')
    {
    }

    protected function getEqualityValues(): array
    {
        return ['cents' => $this->cents, 'currency' => $this->currency];
    }
}

final class PatientRegistered extends DomainEvent
{
    public function __construct(public readonly string $name)
    {
        parent::__construct();
    }
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

final class Doctor extends AggregateRoot
{
}

final class AdultSpecification extends Specification
{
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return is_int($candidate) && $candidate >= 18;
    }
}

final class SeniorSpecification extends Specification
{
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return is_int($candidate) && $candidate >= 65;
    }
}

final class RegisterPatient extends Command
{
}

final class FindPatient extends Query
{
}
