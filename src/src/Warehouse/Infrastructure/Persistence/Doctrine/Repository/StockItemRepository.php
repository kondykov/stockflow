<?php

namespace StockFlow\Warehouse\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;

class StockItemRepository extends AbstractRepository implements StockItemRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockItem::class);
    }

    public function findBySkuCode(string $code): ?StockItem
	{
		$qb = $this->createQueryBuilder("s")
            ->where("s.sku.code = :code")
            ->setParameter("code", $code);

        return $qb->getQuery()->getOneOrNullResult();
	}

    public function findAllInWarehouse(int $warehouseId, int $page, int $pageSize): Collection
    {
        $qb = $this->createQueryBuilder("s")
            ->innerJoin("s.warehouseProducts", "wp")
            ->where("wp.warehouse = :warehouseId")
            ->setParameter("warehouseId", $warehouseId)
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        return new ArrayCollection($qb->getQuery()->getResult());
    }
}
