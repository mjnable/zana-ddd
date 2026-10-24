<?php

declare(strict_types=1);

namespace Zana\Ddd\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Zana\Ddd\Domain\DomainException;
use Zana\Ddd\Domain\Uuid;
use Zana\Ddd\Tests\Height;
use Zana\Ddd\Tests\Measurement;
use Zana\Ddd\Tests\Money;
use Zana\Ddd\Tests\PatientId;
use Zana\Ddd\Tests\Ticket;

final class ValueTest extends TestCase
{
    // ── ValueObject ────────────────────────────────────────────────────────

    public function testValueObjectsAreEqualByValue(): void
    {
        $this->assertTrue(new Height(180.0)->equals(new Height(180.0)));
        $this->assertFalse(new Height(180.0)->equals(new Height(181.0)));
        $this->assertFalse(new Height(180.0)->equals(new Height(180.0, 'in')));
    }

    public function testComparisonIsStrictForScalars(): void
    {
        $this->assertFalse(new Money(100, 'EUR')->equals(new Money(100, 'eur')));
        $this->assertFalse(new Height(1.0)->equals(new Money(1, 'cm')), 'different classes');
    }

    public function testNestedValuesIdentitiesListsAndDatesCompareByValue(): void
    {
        $id = PatientId::generate();
        $at = new \DateTimeImmutable('2026-10-24 10:00');

        $a = new Measurement(new Height(180.0), $id, [1, 2], $at);
        $b = new Measurement(new Height(180.0), PatientId::fromString((string) $id), [1, 2], new \DateTimeImmutable('2026-10-24 10:00'));

        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals(new Measurement(new Height(180.0), $id, [2, 1], $at)));
        $this->assertFalse($a->equals(new Measurement(new Height(180.0), PatientId::generate(), [1, 2], $at)));
    }

    public function testEqualityValuesCanBeOverridden(): void
    {
        $this->assertTrue(new Money(100, 'EUR', 'rent')->equals(new Money(100, 'EUR', 'deposit')));
    }

    // ── Uuid and Identity ──────────────────────────────────────────────────

    public function testUuidV7HasTheVersionAndVariantBits(): void
    {
        $uuid = Uuid::v7();

        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testUuidV7EncodesTheCurrentTime(): void
    {
        $before = (int) floor(microtime(true) * 1000);
        $uuid = Uuid::v7();
        $after = (int) floor(microtime(true) * 1000);

        $milliseconds = (int) hexdec(str_replace('-', '', substr($uuid, 0, 13)));

        $this->assertGreaterThanOrEqual($before, $milliseconds);
        $this->assertLessThanOrEqual($after + 1, $milliseconds);
    }

    public function testUuidV7IsStrictlyIncreasingWithinAProcess(): void
    {
        $uuids = [];
        for ($i = 0; $i < 5000; $i++) {
            $uuids[] = Uuid::v7();
        }

        $sorted = $uuids;
        sort($sorted);

        $this->assertSame($uuids, $sorted);
        $this->assertCount(5000, array_unique($uuids));
    }

    public function testUuidValidation(): void
    {
        $this->assertTrue(Uuid::isValid('550e8400-e29b-41d4-a716-446655440000'));
        $this->assertTrue(Uuid::isValid('00000000-0000-0000-0000-000000000000'));
        $this->assertTrue(Uuid::isValid('FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF'));
        $this->assertFalse(Uuid::isValid('550e8400-e29b-91d4-a716-446655440000'), 'version 9');
        $this->assertFalse(Uuid::isValid('550e8400-e29b-41d4-c716-446655440000'), 'variant 110x');
        $this->assertFalse(Uuid::isValid('not-a-uuid'));
    }

    public function testIdentitiesAreGeneratedParsedAndCompared(): void
    {
        $id = PatientId::generate();
        $parsed = PatientId::fromString(strtoupper((string) $id));

        $this->assertTrue($id->equals($parsed), 'parsing lower-cases');
        $this->assertSame($id->value, (string) $parsed);
        $this->assertFalse($id->equals(PatientId::generate()));
    }

    public function testIdentitiesRejectInvalidValues(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('"nope" is not a valid ' . PatientId::class);

        PatientId::fromString('nope');
    }

    public function testIdentityValidationCanBeOverridden(): void
    {
        $this->assertSame('t-12', (string) Ticket::fromString('T-12'), 'values are lower-cased');

        $this->expectException(DomainException::class);
        Ticket::fromString(Uuid::v7());
    }
}
