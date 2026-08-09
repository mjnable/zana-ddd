<?php
declare(strict_types=1);

namespace Zana\Ddd\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Zana\Ddd\Domain\Entity;
use Zana\Ddd\Domain\DomainEvent;

class EntityTest extends TestCase
{
    public function testEntityWithNullIdUsesReferenceEquality(): void
    {
        $entity1 = new TestEntity();
        $entity2 = new TestEntity();

        $this->assertTrue($entity1->equals($entity1));
        $this->assertFalse($entity1->equals($entity2));
    }

    public function testEntitiesWithSameIdAreEqual(): void
    {
        $entity1 = new TestEntity('id-1');
        $entity2 = new TestEntity('id-1');

        $this->assertTrue($entity1->equals($entity2));
    }

    public function testEntitiesWithDifferentIdsAreNotEqual(): void
    {
        $entity1 = new TestEntity('id-1');
        $entity2 = new TestEntity('id-2');

        $this->assertFalse($entity1->equals($entity2));
    }

    public function testEntitiesOfDifferentClassesWithSameIdAreNotEqual(): void
    {
        $entity1 = new TestEntity('id-1');
        $entity2 = new OtherTestEntity('id-1');

        $this->assertFalse($entity1->equals($entity2));
    }

    public function testGetId(): void
    {
        $entity = new TestEntity('custom-id');
        $this->assertEquals('custom-id', $entity->getId());
    }

    public function testRecordAndReleaseEvents(): void
    {
        $entity = new TestEntity();
        $event = new EntityTestDomainEvent();
        $entity->raiseEvent($event);

        $events = $entity->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertSame($event, $events[0]);
    }

    public function testReleaseEventsClearsEvents(): void
    {
        $entity = new TestEntity();
        $entity->raiseEvent(new EntityTestDomainEvent());
        $entity->releaseEvents();

        $this->assertEmpty($entity->releaseEvents());
    }

    public function testClearEvents(): void
    {
        $entity = new TestEntity();
        $entity->raiseEvent(new EntityTestDomainEvent());
        $entity->clearEvents();

        $this->assertEmpty($entity->releaseEvents());
    }
}

class TestEntity extends Entity
{
    public function raiseEvent(DomainEvent $event): void
    {
        $this->recordEvent($event);
    }
}

class OtherTestEntity extends Entity
{
}

class EntityTestDomainEvent extends DomainEvent
{
}
