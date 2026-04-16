<?php

namespace StockFlow\Warehouse\Domain\Repository;

use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;
use StockFlow\Warehouse\Domain\Aggregate\Stock;

interface StockRepositoryInterface extends RepositoryInterface
{
    public function findByWarehouseIdAndStockItemId(int $warehouseId, int $stockItemId): ?Stock;
}
