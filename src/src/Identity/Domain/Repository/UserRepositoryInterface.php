<?php

namespace StockFlow\Identity\Domain\Repository;

use StockFlow\Identity\Domain\Entity\User;
use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;

/** @implements RepositoryInterface<User>  */
interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?User;
}
