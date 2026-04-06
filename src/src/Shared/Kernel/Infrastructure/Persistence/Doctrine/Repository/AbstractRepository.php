<?php

namespace StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;

abstract class AbstractRepository extends ServiceEntityRepository implements RepositoryInterface
{
    protected EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
        $this->em = $this->getEntityManager();
    }

    public function save(object $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function remove(object $entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function findById(int|string $id): ?object
    {
        return $this->find($id);
    }

    public function findAllPaginated(int $page = 1, int $pageSize = 20): PaginatedResponse
    {
        $page = max(1, $page);
        $pageSize = max(1, min($pageSize, 100));

        $total = $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $items = $this->createQueryBuilder('e')
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();

        $totalPages = (int) ceil($total / $pageSize);

        return new PaginatedResponse(
            page: $page,
            perPage: $pageSize,
            totalCount: $total,
            totalPages: $totalPages,
            hasMorePages: $page >= $totalPages,
            items: $items
        );
    }
}
