<?php

namespace StockFlow\Warehouse\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
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
            ->andWhere('s.product = :productId')
            ->setParameter('warehouseId', $warehouseId)
            ->setParameter('productId', $productId);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
