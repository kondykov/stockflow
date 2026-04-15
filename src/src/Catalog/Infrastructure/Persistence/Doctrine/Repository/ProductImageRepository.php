<?php

namespace StockFlow\Catalog\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Catalog\Domain\Entity\ProductImage;
use StockFlow\Catalog\Domain\Repository\ProductImageRepositoryInterface;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;

class ProductImageRepository extends AbstractRepository implements ProductImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    public function deleteById(int $id): void
    {
        $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->delete();
    }
}
