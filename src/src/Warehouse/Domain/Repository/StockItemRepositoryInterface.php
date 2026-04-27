<?php

namespace StockFlow\Warehouse\Domain\Repository;

use Doctrine\Common\Collections\Collection;
use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Warehouse\Domain\Entity\StockItem;

interface StockItemRepositoryInterface extends RepositoryInterface
{
    public function findBySkuCode(string $code): ?StockItem;

    /**
     * @param int[] $ids 
     * @return PaginatedResponse<StockItem>
     */
    public function findByIdsPaginated(array $ids, int $page, int $pageSize): PaginatedResponse;
}
