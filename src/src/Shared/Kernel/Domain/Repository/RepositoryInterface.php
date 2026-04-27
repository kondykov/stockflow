<?php

namespace StockFlow\Shared\Kernel\Domain\Repository;

use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;

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

    public function findAllPaginated(int $page = 1, int $pageSize = 20): PaginatedResponse;

    public function findAllPaginatedWithSearch(
        int $page = 1,
        int $pageSize = 20,
        ?string $search = null
    ): PaginatedResponse;
}
