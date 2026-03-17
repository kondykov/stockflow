<?php

namespace StockFlow\Warehouse\Domain\Repository;

use Doctrine\Common\Collections\Collection;
use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;
use StockFlow\Warehouse\Domain\Entity\Product;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function findBySkuCode(string $code): ?Product;

    public function findAllInWarehouse(int $warehouseId, int $page, int $pageSize): Collection;
}
