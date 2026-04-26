<?php

namespace StockFlow\Warehouse\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\WarehouseExtractor;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if (!$wh) {
            throw new NotFoundHttpException('Склад не найден');
        }

        return $this->extractor->extract($wh);
    }
}
