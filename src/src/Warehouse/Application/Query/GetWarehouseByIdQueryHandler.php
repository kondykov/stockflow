<?php

namespace StockFlow\Warehouse\Application\Query;

use Assert\Assert;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\WarehouseExtractor;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;

final readonly class GetWarehouseByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private WarehouseExtractor $extractor,
        private WarehouseRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetWarehouseByIdQuery $query): WarehouseResponse
    {
        $wh = $this->repository->findById($query->id);

        Assert::that($wh, defaultPropertyPath: 'id')
            ->notNull('Склад с id %s не найден');

        return $this->extractor->extract($wh);
    }
}
