<?php

namespace StockFlow\Warehouse\Domain\Repository;

use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;
use StockFlow\Warehouse\Domain\Aggregate\Stock;

interface StockRepositoryInterface extends RepositoryInterface
{
    public function findByWarehouseIdAndStockItemId(int $warehouseId, int $stockItemId): ?Stock;

    /**
     * @param int[] $ids
     * @return PaginatedResponse<Stock>
     */
    public function findByIdsPaginated(array $ids, int $page, int $pageSize): PaginatedResponse;
}
