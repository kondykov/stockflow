<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use Assert\Assert;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\StockExtractor;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\StockResponse;

readonly class OutgoingProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StockExtractor $extractor,

        private StockRepositoryInterface $stockRepository,
    ) {
    }

    public function __invoke(OutgoingProductCommand $command): StockResponse
    {
        $stock = $this->stockRepository->findByWarehouseIdAndProductId($command->warehouseId, $command->productId);

        Assert::that($stock, defaultPropertyPath: 'stock')->notEmpty('Остаток не найден');

        $stock->deduct($command->quantity);

        $this->stockRepository->save($stock);

        return $this->extractor->extract($stock);
    }
}
