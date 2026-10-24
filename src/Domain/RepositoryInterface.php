<?php

declare(strict_types=1);

namespace Zana\Ddd\Domain;

/**
 * Collection-like access to the aggregates of one type.
 *
 * @template T of AggregateRoot
 */
interface RepositoryInterface
{
    /**
     * @return T|null
     */
    public function find(mixed $id): ?AggregateRoot;

    /**
     * @return T
     * @throws EntityNotFoundException
     */
    public function get(mixed $id): AggregateRoot;

    /**
     * @param T $aggregate
     */
    public function add(AggregateRoot $aggregate): void;

    /**
     * @param T $aggregate
     */
    public function remove(AggregateRoot $aggregate): void;
}
