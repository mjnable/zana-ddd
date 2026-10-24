<?php

declare(strict_types=1);

namespace Zana\Ddd\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Zana\Ddd\Domain\DomainError;
use Zana\Ddd\Domain\DomainException;
use Zana\Ddd\Domain\EntityNotFoundException;
use Zana\Ddd\Domain\Result;
use Zana\Ddd\Tests\AdultSpecification;
use Zana\Ddd\Tests\PatientId;
use Zana\Ddd\Tests\SeniorSpecification;

final class ResultAndSpecificationTest extends TestCase
{
    // ── Result ─────────────────────────────────────────────────────────────

    public function testSuccessCarriesItsValue(): void
    {
        $result = Result::ok(42);

        $this->assertTrue($result->isOk());
        $this->assertFalse($result->isFail());
        $this->assertSame(42, $result->value());
    }

    public function testFailureCarriesItsError(): void
    {
        $error = new DomainError('bmi.height', 'Height must be positive.', ['height' => -1]);
        $result = Result::fail($error);

        $this->assertTrue($result->isFail());
        $this->assertSame($error, $result->error());
        $this->assertSame(['error', 'oops'], [Result::fail('oops')->error()->code, Result::fail('oops')->error()->message]);
    }

    public function testTheValueOfAFailureCannotBeRead(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cannot read the value of a failed result: oops');

        Result::fail('oops')->value();
    }

    public function testASuccessHasNoError(): void
    {
        $this->expectException(DomainException::class);

        Result::ok(1)->error();
    }

    public function testMapAndThenTransformSuccesses(): void
    {
        $result = Result::ok(80.0)
            ->map(static fn (float $kg): float => $kg / (1.8 * 1.8))
            ->then(static fn (float $bmi): Result => $bmi < 30 ? Result::ok(round($bmi, 1)) : Result::fail('obese'));

        $this->assertSame(24.7, $result->value());
    }

    public function testFailuresPassThroughMapAndThen(): void
    {
        $called = false;
        $result = Result::fail('first')
            ->map(static function () use (&$called) {
                $called = true;

                return 1;
            })
            ->then(static fn (): Result => Result::ok(2));

        $this->assertFalse($called);
        $this->assertSame('first', $result->error()->message);
    }

    public function testThenPropagatesTheChainedFailure(): void
    {
        $result = Result::ok(5)->then(static fn (int $n): Result => Result::fail("{$n} is too small"));

        $this->assertSame('5 is too small', $result->error()->message);
    }

    // ── Specification ──────────────────────────────────────────────────────

    public function testSpecificationsCompose(): void
    {
        $adult = new AdultSpecification();
        $senior = new SeniorSpecification();
        $workingAge = $adult->and($senior->not());

        $this->assertTrue($workingAge->isSatisfiedBy(40));
        $this->assertFalse($workingAge->isSatisfiedBy(70));
        $this->assertFalse($workingAge->isSatisfiedBy(10));

        $childOrSenior = $adult->not()->or($senior);
        $this->assertTrue($childOrSenior->isSatisfiedBy(10));
        $this->assertTrue($childOrSenior->isSatisfiedBy(70));
        $this->assertFalse($childOrSenior->isSatisfiedBy(40));
    }

    public function testEntityNotFoundNamesTheTypeAndId(): void
    {
        $id = PatientId::generate();

        $this->assertSame("Patient with id {$id} was not found.", EntityNotFoundException::for('Patient', $id)->getMessage());
        $this->assertSame('Patient with id array was not found.', EntityNotFoundException::for('Patient', [1])->getMessage());
    }
}
