<?php

namespace StockFlow\Identity\Domain\Repository;

use StockFlow\Identity\Domain\Entity\RBAC\Role;
use StockFlow\Shared\Domain\Repository\RepositoryInterface;

/** @implements RepositoryInterface<Role>  */
interface RoleRepositoryInterface extends RepositoryInterface
{
    public function findByName(string $name): ?Role;
}
