<?php

namespace StockFlow\Warehouse\Infrastructure\Persistence\Doctrine\Repository;

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
}
