<?php

namespace StockFlow\Warehouse\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;

class WarehouseRepository extends AbstractRepository implements WarehouseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warehouse::class);
    }

    public function findByNameAndAddress(string $name, string $address): ?Warehouse
    {
        $qb = $this->createQueryBuilder('w')
            ->andWhere('w.name = :name')
            ->andWhere('w.address = :address')
            ->setParameter('name', $name)
            ->setParameter('address', $address);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
