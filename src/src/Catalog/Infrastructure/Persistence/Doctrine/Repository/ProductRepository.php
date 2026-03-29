<?php

namespace StockFlow\Catalog\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use StockFlow\Catalog\Domain\Entity\Product;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Kernel\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findBySkuCode(string $code): ?Product
	{
        $qb = $this->createQueryBuilder("p")
            ->where("p.sku.code = :code")
            ->setParameter("code", $code);

        return $qb->getQuery()->getOneOrNullResult();
	}
}
