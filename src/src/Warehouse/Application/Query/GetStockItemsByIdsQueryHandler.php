<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Warehouse\Application\Extractor\StockItemExtractor;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\StockItemResponse;
use StockFlow\Warehouse\Application\Query\GetStockItemsByIdsQuery;

/**
 * @implements QueryHandlerInterface<GetStockItemsByIdsQuery, PaginatedResponse<StockItemResponse>>
 */
final readonly class GetStockItemsByIdsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly StockItemExtractor $extractor,
        private readonly StockItemRepositoryInterface $repository
    ) {
    }

    public function __invoke(GetStockItemsByIdsQuery $query): PaginatedResponse
    {
        $result = $this->repository->findByIdsPaginated(
            $query->ids,
            $query->page,
            $query->pageSize
        );

        $items = array_map(fn(StockItem $entity) => $this->extractor->extract($entity), $result->items);

        return new PaginatedResponse(
            page: $result->page,
            perPage: $result->perPage,
            totalCount: $result->totalCount,
            totalPages: $result->totalPages,
            hasMorePages: $result->hasMorePages,
            items: $items,
        );
    }
}

