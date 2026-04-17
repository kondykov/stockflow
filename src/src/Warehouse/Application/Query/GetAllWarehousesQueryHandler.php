<?php

namespace StockFlow\Warehouse\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Warehouse\Application\Extractor\WarehouseExtractor;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;

final readonly class GetAllWarehousesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private WarehouseExtractor $extractor,
        private WarehouseRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetAllWarehousesQuery $query): PaginatedResponse
    {
        $result = $this->repository->findAllPaginated($query->page, $query->pageSize, $query->search);

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
