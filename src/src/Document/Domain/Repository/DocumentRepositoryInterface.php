<?php

namespace StockFlow\Document\Domain\Repository;

use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;

interface DocumentRepositoryInterface
{
    public function findByWarehouse(int $warehouseId, int $page, int $pageSize): PaginatedResponse;
}
