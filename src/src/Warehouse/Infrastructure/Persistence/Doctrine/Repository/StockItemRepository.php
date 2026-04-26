<?php

namespace StockFlow\Warehouse\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
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

    public function findByIdsPaginated(array $ids, int $page, int $pageSize): PaginatedResponse
    {
        $page = max(1, $page);
        $pageSize = max(1, min($pageSize, 100));

        $totalQb = $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->where('s.id IN (:ids)')
            ->setParameter('ids', $ids);

        $total = (int) $totalQb->getQuery()->getSingleScalarResult();

        $qb = $this->createQueryBuilder('s')
            ->where('s.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $items = $qb->getQuery()->getResult();

        $totalPages = (int) ceil($total / $pageSize);

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
