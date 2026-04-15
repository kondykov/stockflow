<?php

namespace StockFlow\Catalog\Domain\Repository;

use StockFlow\Catalog\Domain\Entity\Product;
use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function findBySkuCode(string $code): ?Product;
}
