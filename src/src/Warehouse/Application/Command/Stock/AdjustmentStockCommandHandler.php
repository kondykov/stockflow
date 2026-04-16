<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use Assert\Assert;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\StockExtractor;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\StockResponse;

readonly class AdjustmentStockCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StockExtractor $extractor,

        private CurrentUserInterface $currentUser,
        private StockRepositoryInterface $stockRepository,
        private WarehouseRepositoryInterface $warehouseRepository,
    ) {
    }

    public function __invoke(AdjustmentStockCommand $command): StockResponse
    {
        $user = $this->currentUser->getUser();
        $warehouse = $this->warehouseRepository->findById($command->warehouseId);

        Assert::lazy()
            ->that($warehouse, 'warehouse')->notEmpty('Склад не найден')
            ->that($warehouse?->userId === $user->id, 'warehouse_access')->true('У вас нет доступа к этому складу')
            ->verifyNow();
        $stock = $this->stockRepository->findByWarehouseIdAndProductId($command->warehouseId, $command->productId);

        Assert::that($stock, defaultPropertyPath: 'stock')->notEmpty('Остаток не найден');

        $stock->adjust($command->quantity);

        $this->stockRepository->save($stock);

        return $this->extractor->extract($stock);
    }
}
