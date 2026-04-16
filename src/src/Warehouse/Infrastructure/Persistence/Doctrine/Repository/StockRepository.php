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

    public function findByWarehouseIdAndProductId(int $warehouseId, int $productId): ?Stock
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.warehouse = :warehouseId')
            ->andWhere('s.item = :productId')
            ->setParameter('warehouseId', $warehouseId)
            ->setParameter('productId', $productId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findByWarehouseIdPaginated(int $warehouseId, int $page = 1, int $pageSize = 20): PaginatedResponse
    {
        $page = max(1, $page);
        $pageSize = max(1, min($pageSize, 100));

        $total = $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->where('s.warehouse = :warehouseId')
            ->setParameter('warehouseId', $warehouseId)
            ->getQuery()
            ->getSingleScalarResult();

        $items = $this->createQueryBuilder('s')
            ->where('s.warehouse = :warehouseId')
            ->setParameter('warehouseId', $warehouseId)
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();

        $totalPages = (int)ceil($total / $pageSize);

        return new PaginatedResponse(
            page: $page,
            perPage: $pageSize,
            totalCount: $total,
            totalPages: $totalPages,
            hasMorePages: $page < $totalPages,
            items: $items
        );
    }
}
