<?php

namespace StockFlow\Warehouse\Application\Query;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use StockFlow\Warehouse\Application\Extractor\StockItemExtractor;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;

final readonly class GetAllStocksQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private StockItemExtractor $extractor,
        private StockItemRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetAllStocksQuery $query): PaginatedResponse
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
