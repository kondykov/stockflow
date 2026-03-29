<?php

namespace StockFlow\Warehouse\Application\Query;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\StockItemExtractor;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;

final readonly class GetAllProductsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private StockItemExtractor $extractor,
        private StockItemRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetAllProductsQuery $query): Collection
    {
        $products = $this->repository->findAllPaginated($query->page, $query->pageSize);

        $collection = [];

        foreach ($products as $product) {
            $collection[] = $this->extractor->extract($product);
        }

        return new ArrayCollection($collection);
    }
}
