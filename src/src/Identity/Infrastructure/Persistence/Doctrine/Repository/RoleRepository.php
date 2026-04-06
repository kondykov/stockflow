<?php

namespace StockFlow\Identity\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Identity\Domain\Entity\RBAC\Role;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;

class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    public function findByName(string $name): ?Role
    {
        $qb = $this->createQueryBuilder("r")
            ->where("r.name = :name")
            ->setParameter("name", $name)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findIdsByNames(array $names): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.name IN (:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findByNames(array $names): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.name IN (:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();
    }
}
