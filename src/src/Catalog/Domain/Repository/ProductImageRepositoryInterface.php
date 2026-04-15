<?php

namespace StockFlow\Catalog\Domain\Repository;

use StockFlow\Shared\Kernel\Domain\Repository\RepositoryInterface;

interface ProductImageRepositoryInterface extends RepositoryInterface
{
    public function deleteById(int $id): void;
}
