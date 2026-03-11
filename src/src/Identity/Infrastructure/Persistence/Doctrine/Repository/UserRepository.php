<?php

namespace StockFlow\Identity\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Identity\Domain\Entity\User;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Shared\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByEmail(string $email): ?User
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
