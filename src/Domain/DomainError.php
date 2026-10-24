<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Why a domain operation failed, carried by a failed Result.
 */
final readonly class DomainError
{
    /**
     * @param string $code Stable, machine-readable code, e.g. "bmi.height_out_of_range"
     * @param array<string, mixed> $details
     */
    public function __construct(
        public string $code,
        public string $message,
        public array $details = [],
    ) {
    }
}
