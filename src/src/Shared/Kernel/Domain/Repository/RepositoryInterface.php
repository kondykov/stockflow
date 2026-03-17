<?php

namespace StockFlow\Shared\Kernel\Domain\Repository;

use Doctrine\Common\Collections\Collection;

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

    public function findAllPaginated(int $page, int $pageSize): Collection;
}
