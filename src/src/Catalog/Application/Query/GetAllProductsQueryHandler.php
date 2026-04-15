<?php

namespace StockFlow\Catalog\Application\Query;

use StockFlow\Catalog\Application\Extractor\ProductExtractor;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;

final readonly class GetAllProductsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductExtractor $extractor,
        private ProductRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetAllProductsQuery $query): PaginatedResponse
    {
        $result = $this->repository->findAllPaginated($query->page, $query->pageSize);

        $extractedEntities = [];

        foreach ($result->items as $product) {
            $extractedEntities[] = $this->extractor->extract($product);
        }

        return new PaginatedResponse(
            page: $result->page,
            perPage: $result->perPage,
            totalCount: $result->totalCount,
            totalPages: $result->totalPages,
            hasMorePages: $result->hasMorePages,
            items: $extractedEntities,
        );
    }
}
