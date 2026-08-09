<?php

declare(strict_types=1);

namespace Zana\Ddd\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Zana\Ddd\Domain\ValueObject;

class ValueObjectTest extends TestCase
{
    public function testValueObjectsWithSameValuesAreEqual(): void
    {
        $a = new TestValueObject('value', 42);
        $b = new TestValueObject('value', 42);

        $this->assertTrue($a->equals($b));
    }

    public function testValueObjectsWithDifferentValuesAreNotEqual(): void
    {
        $a = new TestValueObject('value', 42);
        $b = new TestValueObject('different', 42);

        $this->assertFalse($a->equals($b));
    }

    public function testValueObjectsOfDifferentClassesAreNotEqual(): void
    {
        $a = new TestValueObject('value', 42);
        $b = new OtherTestValueObject('value', 42);

        $this->assertFalse($a->equals($b));
    }

    public function testWithReturnsNewInstance(): void
    {
        $a = new TestValueObject('value', 42);
        $b = $a->with(['number' => 43]);

        $this->assertNotSame($a, $b);
        $this->assertEquals('value', $b->getString());
        $this->assertEquals(43, $b->getNumber());
        // Original unchanged
        $this->assertEquals(42, $a->getNumber());
    }

    public function testWithUsesDefaultEqualityValues(): void
    {
        $a = new DefaultValueObject('x');
        $b = new DefaultValueObject('x');

        $this->assertTrue($a->equals($b));
    }
}

class TestValueObject extends ValueObject
{
    public function __construct(
        private string $string,
        private int $number
    ) {
    }

    protected function getEqualityValues(): array
    {
        return ['string' => $this->string, 'number' => $this->number];
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}

class OtherTestValueObject extends ValueObject
{
    public function __construct(
        private string $string,
        private int $number
    ) {
    }
}

class DefaultValueObject extends ValueObject
{
    public function __construct(private string $value)
    {
    }
}

