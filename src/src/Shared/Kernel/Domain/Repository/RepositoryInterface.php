<?php

namespace StockFlow\Shared\Kernel\Domain\Repository;

/**
 * @template T of object
 */
interface RepositoryInterface
{
    /**
     * @param T $entity
     */
    public function save(object $entity): void;
    /**
     * @param T $entity
     */
    public function remove(object $entity): void;

    /**
     * @param int|string $id
     * @return ?T
     */
    public function findById(int|string $id): ?object;
}
