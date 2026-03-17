<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use Assert\Assert;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\StockExtractor;
use StockFlow\Warehouse\Domain\Aggregate\Stock;
use StockFlow\Warehouse\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\StockResponse;

readonly class IncomingProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StockExtractor $extractor,

        private StockRepositoryInterface $stockRepository,
        private ProductRepositoryInterface $productRepository,
        private WarehouseRepositoryInterface $warehouseRepository,
    ) {
    }

    public function __invoke(IncomingProductCommand $command): StockResponse
    {
        $stock = $this->stockRepository->findByWarehouseIdAndProductId($command->warehouseId, $command->productId);

        if ($stock) {
            $stock->receive($command->quantity);
        } else {
            $wh = $this->warehouseRepository->findById($command->warehouseId);
            $product = $this->productRepository->findById($command->productId);

            Assert::lazy()
                ->that($product, 'productId')->notEmpty('Продукт не найден')
                ->that($wh, 'warehouseId')->notEmpty('Склад не найден')
                ->verifyNow();

            $stock = new Stock(
                warehouse: $wh,
                product: $product,
                quantity: $command->quantity,
            );
        }

        $this->stockRepository->save($stock);

        return $this->extractor->extract($stock);
    }
}
