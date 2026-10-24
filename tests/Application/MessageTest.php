<?php

declare(strict_types=1);

namespace Zana\Ddd\Tests\Application;

use PHPUnit\Framework\TestCase;
use Zana\Ddd\Domain\Uuid;
use Zana\Ddd\Tests\FindPatient;
use Zana\Ddd\Tests\PatientRegistered;
use Zana\Ddd\Tests\RegisterPatient;

final class MessageTest extends TestCase
{
    public function testCommandsAndQueriesHaveIdsAndCreationTimes(): void
    {
        $command = new RegisterPatient();
        $query = new FindPatient();

        $this->assertTrue(Uuid::isValid($command->getCommandId()));
        $this->assertTrue(Uuid::isValid($query->getQueryId()));
        $this->assertNotSame($command->getCommandId(), new RegisterPatient()->getCommandId());
        $this->assertLessThanOrEqual(new \DateTimeImmutable(), $command->getCreatedAt());
        $this->assertLessThanOrEqual(new \DateTimeImmutable(), $query->getCreatedAt());
    }

    public function testDomainEventsHaveIdsTimesAndNames(): void
    {
        $event = new PatientRegistered('Ada');

        $this->assertTrue(Uuid::isValid($event->getEventId()));
        $this->assertSame(PatientRegistered::class, $event->getEventName());
        $this->assertLessThanOrEqual(new \DateTimeImmutable(), $event->getOccurredAt());
        $this->assertSame('Ada', $event->name);
    }
}
