<?php

declare(strict_types=1);

namespace Zana\Ddd\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Zana\Ddd\Domain\AggregateRoot;

class AggregateRootTest extends TestCase
{
    public function testVersionStartsAtZero(): void
    {
        $aggregate = new TestAggregateRoot();
        $this->assertEquals(0, $aggregate->getVersion());
    }

    public function testIncrementVersion(): void
    {
        $aggregate = new TestAggregateRoot();
        $aggregate->incrementVersion();

        $this->assertEquals(1, $aggregate->getVersion());
    }

    public function testMultipleIncrements(): void
    {
        $aggregate = new TestAggregateRoot();
        $aggregate->incrementVersion();
        $aggregate->incrementVersion();
        $aggregate->incrementVersion();

        $this->assertEquals(3, $aggregate->getVersion());
    }

    public function testAggregateRootExtendsEntity(): void
    {
        $aggregate = new TestAggregateRoot('id-1');
        $this->assertEquals('id-1', $aggregate->getId());
    }
}

class TestAggregateRoot extends AggregateRoot
{
    public function __construct(mixed $id = null)
    {
        parent::__construct($id);
    }
}

