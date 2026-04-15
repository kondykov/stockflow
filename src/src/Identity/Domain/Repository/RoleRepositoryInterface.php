<?php

namespace StockFlow\Identity\Domain\Repository;

use StockFlow\Identity\Domain\Entity\RBAC\Role;
use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;

/** @implements RepositoryInterface<Role>  */
interface RoleRepositoryInterface extends RepositoryInterface
{
    public function findByName(string $name): ?Role;
    /** @return array<Role> */
    public function findByNames(array $names): array;
    public function findIdsByNames(array $names): array;
}
