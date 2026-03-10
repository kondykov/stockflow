<?php

namespace StockFlow\Identity\Infrastructure\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Identity\Domain\Entity\User;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Shared\Infrastructure\Repository\AbstractRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
