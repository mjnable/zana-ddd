<?php

declare(strict_types=1);

namespace Zana\Ddd\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Zana\Ddd\Tests\Doctor;
use Zana\Ddd\Tests\Patient;
use Zana\Ddd\Tests\PatientId;
use Zana\Ddd\Tests\PatientRegistered;

final class EntityTest extends TestCase
{
    public function testEntitiesWithTheSameClassAndIdAreEqual(): void
    {
        $id = PatientId::generate();

        $this->assertTrue(new Patient($id)->equals(new Patient(PatientId::fromString((string) $id))));
        $this->assertTrue(new Patient(7)->equals(new Patient(7)));
        $this->assertFalse(new Patient(7)->equals(new Patient(8)));
        $this->assertFalse(new Patient(7)->equals(new Doctor(7)), 'different classes');
    }

    public function testEntitiesWithoutIdAreOnlyEqualToThemselves(): void
    {
        $patient = new Patient();

        $this->assertTrue($patient->equals($patient));
        $this->assertFalse($patient->equals(new Patient()));
        $this->assertFalse($patient->equals(new Patient(1)));
        $this->assertNull($patient->getId());
    }

    public function testRecordedEventsAreReleasedOnce(): void
    {
        $patient = Patient::register(PatientId::generate(), 'Ada');

        $events = $patient->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(PatientRegistered::class, $events[0]);
        $this->assertSame([], $patient->releaseEvents());
    }

    public function testEventsCanBeCleared(): void
    {
        $patient = Patient::register(PatientId::generate(), 'Ada');
        $patient->clearEvents();

        $this->assertSame([], $patient->releaseEvents());
    }

    public function testAggregateVersionIncrements(): void
    {
        $patient = new Patient(1);
        $this->assertSame(0, $patient->getVersion());

        $patient->incrementVersion();
        $patient->incrementVersion();

        $this->assertSame(2, $patient->getVersion());
    }
}
