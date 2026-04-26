<?php

namespace StockFlow\Warehouse\Domain\Repository;

use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Warehouse\Domain\Entity\Warehouse;

interface WarehouseRepositoryInterface extends RepositoryInterface
{
    public function findByNameAndAddress(string $name, string $address): ?Warehouse;
}
