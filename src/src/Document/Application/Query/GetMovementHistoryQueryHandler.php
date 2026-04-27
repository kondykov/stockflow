<?php

namespace StockFlow\Document\Application\Query;

use StockFlow\Document\Domain\Repository\DocumentRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;

readonly class GetMovementHistoryQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository
    ) {}

    public function __invoke(GetMovementHistoryQuery $query): PaginatedResponse
    {
        return $this->documentRepository->findByWarehouse(
            $query->warehouseId,
            $query->page,
            $query->pageSize
        );
    }
}
