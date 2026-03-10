<?php

namespace StockFlow\Shared\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Shared\Domain\Repository\RepositoryInterface;
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
}
