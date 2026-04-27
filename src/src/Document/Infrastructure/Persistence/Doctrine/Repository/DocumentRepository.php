<?php

namespace StockFlow\Document\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Document\Domain\Entity\BaseDocument;
use StockFlow\Document\Domain\Entity\InventoryMovementDocument;
use StockFlow\Document\Domain\Repository\DocumentRepositoryInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;

class DocumentRepository extends AbstractRepository implements DocumentRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseDocument::class);
    }

    public function findByWarehouse(int $warehouseId, int $page, int $pageSize): PaginatedResponse
    {
        $page = max(1, $page);
        $pageSize = max(1, min($pageSize, 100));

        $totalQb = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(d.id)')
            ->from(InventoryMovementDocument::class, 'd')
            ->where('d.warehouseId = :warehouseId')
            ->setParameter('warehouseId', $warehouseId);

        $total = (int)$totalQb->getQuery()->getSingleScalarResult();

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('d')
            ->from(InventoryMovementDocument::class, 'd')
            ->where('d.warehouseId = :warehouseId')
            ->setParameter('warehouseId', $warehouseId)
            ->orderBy('d.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $items = $qb->getQuery()->getResult();
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
