<?php

namespace StockFlow\Warehouse\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use StockFlow\Warehouse\Domain\Aggregate\Stock;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;

class StockRepository extends AbstractRepository implements StockRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }


    public function findByWarehouseIdAndStockItemId(int $warehouseId, int $stockItemId): ?Stock
    {
        return $this->createQueryBuilder('s')
            ->where('s.warehouse = :warehouseId')
            ->andWhere('s.item = :stockItemId')
            ->setParameter('warehouseId', $warehouseId)
            ->setParameter('stockItemId', $stockItemId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByIdsPaginated(array $ids, int $page, int $pageSize): PaginatedResponse
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.item', 'i')
            ->where('i.id IN (:itemIds)')
            ->setParameter('itemIds', $ids);

        return $this->paginateQueryBuilder($qb, $page, $pageSize);
    }

    public function findByWarehouseIdPaginated(
        int $warehouseId,
        int $page = 1,
        int $pageSize = 20,
        ?string $search = null
    ): PaginatedResponse {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.item', 'i')
            ->where('s.warehouse = :warehouseId')
            ->setParameter('warehouseId', $warehouseId);

        if ($search) {
            $qb->andWhere('i.name LIKE :search OR i.sku LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $this->paginateQueryBuilder($qb, $page, $pageSize);
    }
}
