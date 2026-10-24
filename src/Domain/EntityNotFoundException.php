<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Thrown by RepositoryInterface::get() when no aggregate has the id.
 */
class EntityNotFoundException extends DomainException
{
    public static function for(string $type, mixed $id): self
    {
        $printable = is_scalar($id) || $id instanceof \Stringable ? (string) $id : get_debug_type($id);

        return new self("{$type} with id {$printable} was not found.");
    }
}
