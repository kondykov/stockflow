<?php

namespace StockFlow\Warehouse\Domain\Repository;

use Doctrine\Common\Collections\Collection;
use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;
use StockFlow\Warehouse\Domain\Entity\StockItem;

interface StockItemRepositoryInterface extends RepositoryInterface
{
    public function findBySkuCode(string $code): ?StockItem;

    public function findAllInWarehouse(int $warehouseId, int $page, int $pageSize): Collection;
}
