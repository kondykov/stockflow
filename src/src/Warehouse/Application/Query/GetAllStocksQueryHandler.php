<?php

namespace StockFlow\Warehouse\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Warehouse\Application\Extractor\StockExtractor;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;

final readonly class GetAllStocksQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private StockExtractor $extractor,
        private StockRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetAllStocksQuery $query): PaginatedResponse
    {
        $result = $this->repository->findByWarehouseIdPaginated($query->id, $query->page, $query->pageSize);

        $extractedEntities = [];

        foreach ($result->items as $stock) {
            $extractedEntities[] = $this->extractor->extract($stock);
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
