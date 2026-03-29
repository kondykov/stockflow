<?php

namespace StockFlow\Catalog\Application\Query;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use StockFlow\Catalog\Application\Extractor\ProductExtractor;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;

final readonly class GetAllProductsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductExtractor $extractor,
        private ProductRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetAllProductsQuery $query): array
    {
        $products = $this->repository->findAllPaginated($query->page, $query->pageSize);

        $collection = [];

        foreach ($products as $product) {
            $collection[] = $this->extractor->extract($product);
        }

        return $collection;
    }
}
