<?php

namespace StockFlow\Catalog\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Catalog\Domain\Entity\ProductAttribute;
use StockFlow\Catalog\Domain\Entity\ProductImage;
use StockFlow\Catalog\Domain\Repository\ProductAttributeRepositoryInterface;
use StockFlow\Catalog\Domain\Repository\ProductImageRepositoryInterface;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;

class ProductAttributeRepository extends AbstractRepository implements ProductAttributeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAttribute::class);
    }
}
