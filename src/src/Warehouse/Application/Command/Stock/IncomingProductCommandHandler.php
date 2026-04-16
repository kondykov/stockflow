<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use Assert\Assert;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\StockExtractor;
use StockFlow\Warehouse\Domain\Aggregate\Stock;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\StockResponse;

readonly class IncomingProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StockExtractor $extractor,

        private CurrentUserInterface $currentUser,
        private StockRepositoryInterface $stockRepository,
        private StockItemRepositoryInterface $stockItemRepository,
        private WarehouseRepositoryInterface $warehouseRepository,
    ) {
    }

    public function __invoke(IncomingProductCommand $command): StockResponse
    {
        $user = $this->currentUser->getUser();
        $warehouse = $this->warehouseRepository->findById($command->warehouseId);

        Assert::lazy()
            ->that($warehouse, 'warehouse')->notEmpty('Склад не найден')
            ->that($warehouse?->userId === $user->id, 'warehouse_access')->true('У вас нет доступа к этому складу')
            ->verifyNow();
        $stock = $this->stockRepository->findByWarehouseIdAndProductId($command->warehouseId, $command->productId);

        if ($stock) {
            $stock->receive($command->quantity);
        } else {
            /** @var Warehouse $wh */
            $wh = $this->warehouseRepository->findById($command->warehouseId);
            /** @var StockItem $product */
            $product = $this->stockItemRepository->findById($command->productId);

            Assert::lazy()
                ->that($product, 'productId')->notEmpty('Продукт не найден')
                ->that($wh, 'warehouseId')->notEmpty('Склад не найден')
                ->verifyNow();

            $stock = new Stock(
                warehouse: $wh,
	            item: $product,
                quantity: $command->quantity,
            );
        }

        $this->stockRepository->save($stock);

        return $this->extractor->extract($stock);
    }
}
